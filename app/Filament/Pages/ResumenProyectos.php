<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CasasPorProyectoChart;
use App\Filament\Widgets\CasasStatsOverview;
use Filament\Pages\Page;

class ResumenProyectos extends Page
{
    protected static ?string $navigationGroup = 'Inventario';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Resumen por proyecto';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.pages.resumen-proyectos';

    protected function getHeaderWidgets(): array
    {
        return [
            CasasStatsOverview::class,
            CasasPorProyectoChart::class,
        ];
    }
}
