<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\{User,Product};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    public function index()
    {
        $products       = Product::count();
        $users          = User::count();
        $verified_users = User::whereNot('role', 'admin')->where('is_verified', 1)->count();
        $blocked_users  = User::whereNot('role', 'admin')->where('is_block', 1)->count();
        return view('index', compact('products', 'users', 'blocked_users', 'verified_users'));
    }

    public function users()
    {
        $users = User::whereNot('role', 'admin')->orderBy('id', 'DESC')->paginate(10);
        return view('all-users', compact('users'));
    }

    public function toggleBlock($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->is_block = !$user->is_block;
            $user->update();
            if ($user->is_block) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}
