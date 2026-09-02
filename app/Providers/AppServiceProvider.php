<?php

namespace App\Providers;

use App\Models\Casa;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Contratista;
use App\Models\Entrega;
use App\Models\Garantia;
use App\Models\Proyecto;
use App\Models\Reclamo;
use App\Models\ReporteEntrega;
use App\Models\ReporteReclamo;
use App\Models\TipoCasa;
use App\Models\User;
use App\Observers\CitaObserver;
use App\Observers\EntregaObserver;
use App\Observers\ReporteReclamoObserver;
use App\Policies\CasaPolicy;
use App\Policies\CitaPolicy;
use App\Policies\ClientePolicy;
use App\Policies\ContratistaPolicy;
use App\Policies\EntregaPolicy;
use App\Policies\GarantiaPolicy;
use App\Policies\ProyectoPolicy;
use App\Policies\ReclamoPolicy;
use App\Policies\ReporteEntregaPolicy;
use App\Policies\ReporteReclamoPolicy;
use App\Policies\TipoCasaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
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
        // 1. Configuración de Super Admin (MASTER)
        Gate::before(function ($user, $ability) {
            if ($user->esMaster()) {
                return true;
            }
            return null;
        });

        // 2. Registro de Observers
        Cita::observe(CitaObserver::class);
        Entrega::observe(EntregaObserver::class);
        ReporteReclamo::observe(ReporteReclamoObserver::class);

        // 3. Registro de Policies
        Gate::policy(Proyecto::class, ProyectoPolicy::class);
        Gate::policy(TipoCasa::class, TipoCasaPolicy::class);
        Gate::policy(Casa::class, CasaPolicy::class);
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Garantia::class, GarantiaPolicy::class);
        Gate::policy(Contratista::class, ContratistaPolicy::class);
        Gate::policy(Cita::class, CitaPolicy::class);
        Gate::policy(Entrega::class, EntregaPolicy::class);
        Gate::policy(Reclamo::class, ReclamoPolicy::class);
        Gate::policy(ReporteEntrega::class, ReporteEntregaPolicy::class);
        Gate::policy(ReporteReclamo::class, ReporteReclamoPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}