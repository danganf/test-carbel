<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \DB::listen(function ($query) {
            try {
                \LogDebug::setLogFile('Querie')->info($query->sql, array_merge($query->bindings, ['TIME: ' . $query->time]));
            } catch (\Exception $e){

            }
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
