<?php

namespace App\Http\Controllers\Products;
use App\Http\Controllers\Controller;
use App\Models\{Category, Product, User};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Product::class);
        $categories = Category::orderBy('name')->get();
        $products   = Product::latest()->with('category')->orderBy('id', 'ASC')->paginate(10);
        return view('products.index', compact('products', 'categories'));
    }

    public function trashProducts()
    {
        $this->authorize('trashProducts',Product::class);
        $categories = Category::orderBy('name')->get();
        $products = Product::onlyTrashed()->with('category')->paginate(10);
        return view('products.trashed-products', compact('products','categories'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create',Product::class);

        $validated = Validator::make($request->all(),[
            'category_id' => 'required',
            'name'        => 'required',
            'price'       => 'required',
            'size'        => 'required|array',
            'description' => 'required',
            'image'       => 'required|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validated->fails()){
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        DB::beginTransaction();
        try {
            $validated = $validated->validated();
            if($request->hasFile('image')){
                $validated['image'] = $this->customSaveImage($request->file('image'), 'products/product_image');
            }
            Product::create($validated);
            DB::commit();
            return redirect()->route('products.index')->with('success','Products created successfully.');
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('products.index')->with('error','Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $this->authorize('update', $product);
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(),[
            'category_id' => 'required',
            'name'        => 'required',
            'price'       => 'required',
            'size'        => 'required|array',
            'description' => 'required',
            'image'       => 'nullable|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validated->fails()){
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        DB::beginTransaction();
        try {

            $product   = Product::findOrFail($id);
            $validated = $validated->validated();
            $this->authorize('update', $product);

            if ($request->hasFile('image')){
                $oldImage = $product->image;
                if (file_exists($oldImage)) @unlink($oldImage);
                $validated['image']= $this->customSaveImage($request->file('image'), 'products/product_image');
            }

            $product->update($validated);
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('delete', $product);
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Product Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function view($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $this->authorize('view', $product);
        return view('products.view', compact('product', 'categories'));
    }

    public function search(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::query()->when($request->category_id, function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        })->when($request->name, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%');
        })->when($request->price_from, function ($q) use ($request) {
            $q->where('price', '>=', $request->price_from);
        })->when($request->price_to, function ($q) use ($request) {
            $q->where('price', '<=', $request->price_to);
        })->when($request->size, function ($q) use ($request) {
            $q->whereJsonContains('size', strtoupper($request->size));
        })->orderBy('id', 'ASC')->paginate(10);

        return view('products.index', compact('products', 'categories'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $product);
        DB::beginTransaction();
        try {
            $product->restore();
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product Restored Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $product);
        DB::beginTransaction();
        try {
            $oldImage = $product->image;
            if (file_exists($oldImage)) @unlink($oldImage);
            $product->forceDelete();
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Product Deleted Permanently.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function toggleStatus($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $this->authorize('updateStatus', $product);
            $product->status = !$product->status;
            $product->update();
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    private function customSaveImage($file, $path)
    {
        $filename = uniqid() . time() . rand(10, 1000000) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs($path, $file, $filename);
        $fileUrl = 'storage/' . $path . '/' . $filename;
        return $fileUrl;
    }
}
