<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Product::class  => ProductPolicy::class,
        User::class     => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('admin-user', function ($user) {
            return in_array($user->role, ['admin', 'user']);
        });
    }
}
