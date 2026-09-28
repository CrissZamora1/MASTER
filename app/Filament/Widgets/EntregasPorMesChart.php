<?php

namespace App\Filament\Widgets;

use App\Models\Entrega;
use Filament\Widgets\ChartWidget;

class EntregasPorMesChart extends ChartWidget
{
    protected static ?string $heading = 'Entregas por mes';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return ! auth()->user()?->esContratista();
    }

    protected function getData(): array
    {
        $user = auth()->user();

        $meses = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        $entregadas = [];
        $conReclamos = [];

        foreach ($meses as $mes) {
            $entregadas[] = Entrega::query()
                ->where('resultado', 'entregada')
                ->whereBetween('fecha_hora_entrega', [$mes->copy()->startOfMonth(), $mes->copy()->endOfMonth()])
                ->whereHas('casa', fn ($q) => $q->visiblePara($user))
                ->count();

            $conReclamos[] = Entrega::query()
                ->where('resultado', 'entregada_con_reclamos')
                ->whereBetween('fecha_hora_entrega', [$mes->copy()->startOfMonth(), $mes->copy()->endOfMonth()])
                ->whereHas('casa', fn ($q) => $q->visiblePara($user))
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Entregadas',
                    'data' => $entregadas,
                    'backgroundColor' => '#22c55e',
                ],
                [
                    'label' => 'Con reclamos',
                    'data' => $conReclamos,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $meses->map(fn ($m) => $m->translatedFormat('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}