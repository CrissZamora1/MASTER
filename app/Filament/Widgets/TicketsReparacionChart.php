<?php

namespace App\Filament\Widgets;

use App\Models\ReclamoGarantia;
use Filament\Widgets\ChartWidget;

class TicketsReparacionChart extends ChartWidget
{
    protected static ?string $heading = 'Tickets de reparación';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return ! auth()->user()?->esContratista();
    }

    protected function getData(): array
    {
        $user = auth()->user();

        $datos = ReclamoGarantia::query()
            ->selectRaw('estado_reparacion, count(*) as total')
            ->whereHas('reclamo.casa', fn ($q) => $q->visiblePara($user))
            ->groupBy('estado_reparacion')
            ->pluck('total', 'estado_reparacion')
            ->toArray();

        $estados = [
            'pendiente' => ['label' => 'No iniciado', 'color' => '#ef4444'],
            'en_proceso' => ['label' => 'En proceso', 'color' => '#f59e0b'],
            'finalizada' => ['label' => 'Finalizada', 'color' => '#22c55e'],
        ];

        $data = [];
        $labels = [];
        $colors = [];

        foreach ($estados as $clave => $info) {
            $data[] = $datos[$clave] ?? 0;
            $labels[] = $info['label'];
            $colors[] = $info['color'];
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