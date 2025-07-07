<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kepaniteraan\PeminjamanBerkasPerkara;
use App\Observers\Kepaniteraan\PeminjamanObserver;
use Illuminate\Support\Facades\Gate;

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
        Gate::before(function ($user, $ability) {
        return $user->hasRole('super_admin') ? true : null;
    });
    }
}
