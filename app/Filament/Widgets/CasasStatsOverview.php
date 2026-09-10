<?php

namespace App\Filament\Widgets;

use App\Models\Casa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class CasasStatsOverview extends BaseWidget
{
    public string $proyectoSeleccionado = 'todos';

    #[On('proyecto-filtro-actualizado')]
    public function actualizarProyecto($proyectoId): void
    {
        $this->proyectoSeleccionado = $proyectoId;
    }

    protected function getStats(): array
    {
        $query = Casa::query();

        if ($this->proyectoSeleccionado !== 'todos') {
            $query->where('proyecto_id', $this->proyectoSeleccionado);
        }

        return [
            Stat::make('Disponibles', (clone $query)->where('estado', 'disponible')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('No disponibles', (clone $query)->where('estado', 'no_disponible')->count())
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Con cita programada', (clone $query)->whereIn('estado', ['programada', 'reprogramada'])->count())
                ->icon('heroicon-o-calendar-days')
                ->color('warning'),

            Stat::make('Entregadas', (clone $query)->where('estado', 'entregado')->count())
                ->icon('heroicon-o-key')
                ->color('primary'),

            Stat::make('No asistió', (clone $query)->where('estado', 'no_asistio')->count())
                ->icon('heroicon-o-user-minus')
                ->color('purple'),
        ];
    }
}
