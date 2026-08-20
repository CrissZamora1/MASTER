<?php

namespace App\Filament\Resources\CasaResource\Pages;

use App\Filament\Resources\CasaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCasas extends ListRecords
{
    protected static string $resource = CasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
