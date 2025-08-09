<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register the admin auth middleware
        Route::aliasMiddleware('admin.auth', \App\Http\Middleware\AdminAuthenticate::class);
    }
}
