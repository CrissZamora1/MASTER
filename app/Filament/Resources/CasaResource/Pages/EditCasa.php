<?php

namespace App\Filament\Resources\CasaResource\Pages;

use App\Filament\Resources\CasaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCasa extends EditRecord
{
    protected static string $resource = CasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
