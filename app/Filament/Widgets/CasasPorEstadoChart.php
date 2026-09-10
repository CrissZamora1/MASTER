<?php

namespace App\Filament\Widgets;

use App\Models\Proyecto;
use Filament\Widgets\ChartWidget;

class CasasPorEstadoChart extends ChartWidget
{
    protected static ?string $heading = 'Casas por estado y proyecto';
    public static bool $isDiscovered = false;
    protected function getData(): array
    {
        $proyectos = Proyecto::with('casas')->get();

        $estados = [
            'disponible' => ['label' => 'Disponible', 'color' => '#22c55e'],
            'no_disponible' => ['label' => 'No disponible', 'color' => '#ef4444'],
            'programada' => ['label' => 'Programada', 'color' => '#f59e0b'],
            'reprogramada' => ['label' => 'Reprogramada', 'color' => '#fb923c'],
            'entregado' => ['label' => 'Entregado', 'color' => '#3b82f6'],
            'no_asistio' => ['label' => 'No asistió', 'color' => '#a855f7'],
        ];

        $datasets = [];

        foreach ($estados as $clave => $info) {
            $datasets[] = [
                'label' => $info['label'],
                'data' => $proyectos->map(fn($p) => $p->casas->where('estado', $clave)->count())->toArray(),
                'backgroundColor' => $info['color'],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $proyectos->pluck('nombre')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true],
            ],
        ];
    }
}
