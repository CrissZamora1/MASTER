<?php

namespace App\Filament\Resources\ReporteEntregaResource\Pages;

use App\Filament\Resources\ReporteEntregaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReporteEntregas extends ListRecords
{
    protected static string $resource = ReporteEntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
