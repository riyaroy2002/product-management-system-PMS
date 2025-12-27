<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Products\ProductController;

    /**AUTHENTICATION  */
    Route::middleware('guest')->group(function (){
        Route::get('register'                ,[AuthController::class ,'registerForm'])->name('register');
        Route::post('register'               ,[AuthController::class ,'register'])->name('post.register');
        Route::get('verify-user'             ,[AuthController::class ,'verifyUserForm'])->name('verify-user');
        Route::post('resend-email-link'      ,[AuthController::class ,'resendEmailLink'])->name('resend-email-link');
        Route::get('verify-email/{token}'    ,[AuthController::class ,'verifyEmail'])->name('reset-password');
        Route::get('login'                   ,[AuthController::class ,'loginForm'])->name('login');
        Route::post('login'                  ,[AuthController::class ,'login'])->name('post.login');
        Route::get('forgot-password'         ,[AuthController::class ,'forgotPassword'])->name('forgot-password');
        Route::post('forgot-password'        ,[AuthController::class ,'sendResetLink'])->name('reset-link');
        Route::post('resend-reset-link'      ,[AuthController::class ,'resendResetLink'])->name('resend-link');
        Route::get('reset-password/{token}'  ,[AuthController::class ,'resetPasswordForm'])->name('reset-password');
        Route::post('reset-password'         ,[AuthController::class ,'resetPassword'])->name('post.reset-password');
    });

    Route::middleware(['auth', 'auth-check:admin,user'])->group(function () {
        Route::get('/'                           ,[HomeController::class, 'index'])->name('index');
        /** CATEGORIES  */
        Route::get('/categories'                 ,[CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create-category'            ,[CategoryController::class, 'create'])->name('category.create');
        Route::post('/create-category'           ,[CategoryController::class, 'store'])->name('category.store');
        Route::get('/edit-category/{id}'         ,[CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update-category/{id}'      ,[CategoryController::class, 'update'])->name('category.update');
        Route::post('/delete-category/{id}'      ,[CategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/trash-categories'           ,[CategoryController::class, 'trashCategories'])->name('category.trash-categories');
        Route::post('/restore-category/{id}'     ,[CategoryController::class, 'restore'])->name('category.restore');
        Route::post('/category-force-delete/{id}',[CategoryController::class,'forceDelete'])->name('category.force-delete');
        /** PRODUCTS  */
        Route::get('/products'                   ,[ProductController::class, 'index'])->name('products.index');
        Route::get('/create-product'             ,[ProductController::class, 'create'])->name('product.create');
        Route::post('/create-product'            ,[ProductController::class, 'store'])->name('product.store');
        Route::get('/edit-product/{id}'          ,[ProductController::class, 'edit'])->name('product.edit');
        Route::post('/update-product/{id}'       ,[ProductController::class, 'update'])->name('product.update');
        Route::post('/delete-product/{id}'       ,[ProductController::class, 'destroy'])->name('product.delete');
        Route::get('/view-product/{id}'          ,[ProductController::class, 'view'])->name('product.view');
        Route::post('/search-product'            ,[ProductController::class, 'search'])->name('product.search');
         Route::get('/trash-products'            ,[ProductController::class, 'trashProducts'])->name('product.trash-products');
        Route::post('/product-restore/{id}'      ,[ProductController::class, 'restore'])->name('product.restore');
        Route::post('/force-delete/{id}'         ,[ProductController::class, 'forceDelete'])->name('product.force-delete');
        Route::post('/toggle-status/{id}'        ,[ProductController::class, 'toggleStatus'])->name('product.toggle-status');

        /**USERS  */
        Route::get('/users'                      ,[HomeController::class, 'users'])->name('users.index');
        Route::post('/toggle-block/{id}'         ,[HomeController::class, 'toggleBlock'])->name('users.toggle-block');
    });
    /** LOGOUT  */
    Route::post('/logout'                        ,[AuthController::class   , 'logout'])->name('logout');
