<?php

namespace App\Providers;

use App\Models\Spiel;
use App\Observers\SpielObserver;
use Illuminate\Support\ServiceProvider;

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
        Spiel::observe(SpielObserver::class);

    }
}
