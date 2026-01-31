<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        DB::listen(function ($query) {
            // Registra la consulta en el log de Laravel
            Log::info('Consulta SQL ejecutada: ' . $query->sql);
            Log::info('Bindings: ' . json_encode($query->bindings));
            Log::info('Tiempo: ' . $query->time . 'ms');
        });
    }
}
