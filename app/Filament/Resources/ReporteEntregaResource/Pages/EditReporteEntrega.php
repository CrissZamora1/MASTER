<?php

namespace App\Filament\Resources\ReporteEntregaResource\Pages;

use App\Filament\Resources\ReporteEntregaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReporteEntrega extends EditRecord
{
    protected static string $resource = ReporteEntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
