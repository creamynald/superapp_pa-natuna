<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kepaniteraan\PeminjamanBerkasPerkara;
use App\Observers\Kepaniteraan\PeminjamanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PeminjamanBerkasPerkara::observe(PeminjamanObserver::class);
    }
}
