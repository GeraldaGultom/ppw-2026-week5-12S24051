<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Audit kueri SQL hanya saat mode debug (lokal), dicatat ke storage/logs/laravel.log
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::info(sprintf('[SQL AUDIT] (%.2f ms) %s', $query->time, $query->sql));
            });
        }
    }
}