<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\{Category, User};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function trashCategories()
    {
        $this->authorize('trashCategories',Category::class);
        $categories = Category::onlyTrashed()->paginate(10);
        return view('categories.trashed-categories', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $validated = Validator::make($request->all(),[
            'name' => ['required', Rule::unique('categories', 'name')]
        ]);

        if ($validated->fails()){
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        DB::beginTransaction();
        try {
            Category::create($validated->validated());
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Categories created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(),[
            'name' => ['required', Rule::unique('categories', 'name')->ignore($id)]
        ]);

        if ($validated->fails()){
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $this->authorize('update', $category);
            $category->update($validated->validated());
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);
        DB::beginTransaction();
        try {
            $category->delete();
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $category);
        DB::beginTransaction();
        try {
            $category->restore();
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category Restored Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $category);
        DB::beginTransaction();
        try {
            $category->forceDelete();
            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category Deleted Permanently.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}
