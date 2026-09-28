<?php

namespace App\Filament\Widgets;

use App\Models\Proyecto;
use Filament\Widgets\ChartWidget;

class CasasPorProyectoChart extends ChartWidget
{
    protected static ?string $heading = 'Avance por proyecto';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '320px';

    public static function canView(): bool
    {
        return ! auth()->user()?->esContratista();
    }

    protected function getData(): array
    {
        $user = auth()->user();

        $proyectos = Proyecto::query()
            ->with('casas')
            ->when(
                $user && ! $user->esSuper() && ! $user->esMaster(),
                fn ($q) => $q->whereIn('id', $user->proyectosAsignados()->pluck('proyectos.id')),
            )
            ->get();

        $estados = [
            'disponible' => ['label' => 'Disponible', 'color' => '#22c55e'],
            'no_disponible' => ['label' => 'No disponible', 'color' => '#d1d5db'],
            'programada' => ['label' => 'Programada', 'color' => '#3b82f6'],
            'reprogramada' => ['label' => 'Reprogramada', 'color' => '#a855f7'],
            'entregado' => ['label' => 'Entregado', 'color' => '#0f766e'],
            'entregado_con_reclamos' => ['label' => 'Entregado con reclamos', 'color' => '#f59e0b'],
            'no_asistio' => ['label' => 'No asistió', 'color' => '#ef4444'],
        ];

        $datasets = [];

        foreach ($estados as $clave => $info) {
            $datasets[] = [
                'label' => $info['label'],
                'data' => $proyectos->map(fn ($p) => $p->casas->where('estado', $clave)->count())->toArray(),
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
                'y' => ['stacked' => true, 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}