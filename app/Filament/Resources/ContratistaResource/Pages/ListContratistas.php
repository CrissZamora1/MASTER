<?php

namespace App\Filament\Resources\ContratistaResource\Pages;

use App\Filament\Resources\ContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContratistas extends ListRecords
{
    protected static string $resource = ContratistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
