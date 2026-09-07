<?php

namespace App\Filament\Resources\ReclamoGarantiaResource\Pages;

use App\Filament\Resources\ReclamoGarantiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReclamoGarantias extends ManageRecords
{
    protected static string $resource = ReclamoGarantiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
