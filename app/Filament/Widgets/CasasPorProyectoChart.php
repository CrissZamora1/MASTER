<?php

namespace App\Filament\Widgets;

use App\Models\Casa;
use App\Models\Proyecto;
use Filament\Widgets\ChartWidget;

class CasasPorProyectoChart extends ChartWidget
{
    protected static ?string $heading = 'Distribución de estados';

    public ?string $filter = 'todos';

    protected function getFilters(): ?array
    {
        return Proyecto::pluck('nombre', 'id')
            ->prepend('Todos los proyectos', 'todos')
            ->toArray();
    }

    public function updatedFilter(): void
    {
        $this->dispatch('proyecto-filtro-actualizado', proyectoId: $this->filter);
    }

    protected function getData(): array
    {
        $query = Casa::query();

        if ($this->filter !== 'todos') {
            $query->where('proyecto_id', $this->filter);
        }

        $estados = [
            'disponible' => ['label' => 'Disponible', 'color' => '#22c55e'],
            'no_disponible' => ['label' => 'No disponible', 'color' => '#ef4444'],
            'programada' => ['label' => 'Programada', 'color' => '#f59e0b'],
            'reprogramada' => ['label' => 'Reprogramada', 'color' => '#fb923c'],
            'entregado' => ['label' => 'Entregado', 'color' => '#3b82f6'],
            'no_asistio' => ['label' => 'No asistió', 'color' => '#a855f7'],
        ];

        $data = [];
        $labels = [];
        $colors = [];

        foreach ($estados as $clave => $info) {
            $count = (clone $query)->where('estado', $clave)->count();
            if ($count > 0) {
                $data[] = $count;
                $labels[] = $info['label'];
                $colors[] = $info['color'];
            }
        }

        return [
            'datasets' => [[
                'data' => $data,
                'backgroundColor' => $colors,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
