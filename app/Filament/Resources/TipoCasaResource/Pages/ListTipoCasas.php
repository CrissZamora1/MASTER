<?php

namespace App\Filament\Resources\TipoCasaResource\Pages;

use App\Filament\Resources\TipoCasaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoCasas extends ListRecords
{
    protected static string $resource = TipoCasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
