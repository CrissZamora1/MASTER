<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CasaResource;
use App\Models\Casa;
use App\Models\ReclamoGarantia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CasasStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return ! auth()->user()?->esContratista();
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        $query = Casa::query()->visiblePara($user);

        $ticketsAbiertos = ReclamoGarantia::query()
            ->where('estado_reparacion', '!=', 'finalizada')
            ->whereHas('reclamo.casa', fn ($q) => $q->visiblePara($user))
            ->count();

        return [
            Stat::make('Total de casas', (clone $query)->count())
                ->icon('heroicon-o-home-modern')
                ->color('gray'),

            Stat::make('Disponibles', (clone $query)->where('estado', 'disponible')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->url(CasaResource::getUrl('index', ['tableFilters' => ['estado' => ['value' => 'disponible']]])),

            Stat::make('Con cita programada', (clone $query)->whereIn('estado', ['programada', 'reprogramada'])->count())
                ->icon('heroicon-o-calendar-days')
                ->color('info')
                ->url(CasaResource::getUrl('index', ['tableFilters' => ['estado' => ['value' => 'programada']]])),

            Stat::make('Entregadas', (clone $query)->where('estado', 'entregado')->count())
                ->icon('heroicon-o-key')
                ->color('primary')
                ->url(CasaResource::getUrl('index', ['tableFilters' => ['estado' => ['value' => 'entregado']]])),

            Stat::make('No asistieron', (clone $query)->where('estado', 'no_asistio')->count())
                ->icon('heroicon-o-user-minus')
                ->color('warning')
                ->url(CasaResource::getUrl('index', ['tableFilters' => ['estado' => ['value' => 'no_asistio']]])),

            Stat::make('Tickets abiertos', $ticketsAbiertos)
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('danger'),
        ];
    }
}