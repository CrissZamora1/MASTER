<?php

namespace App\Providers;

use App\Models\Cita;
use App\Models\Entrega;
use App\Observers\CitaObserver;
use App\Observers\EntregaObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Cita::observe(CitaObserver::class);
        Entrega::observe(EntregaObserver::class);
    }
}