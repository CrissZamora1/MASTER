<?php

namespace App\Filament\Resources\ContratistaResource\Pages;

use App\Filament\Resources\ContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContratista extends EditRecord
{
    protected static string $resource = ContratistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
