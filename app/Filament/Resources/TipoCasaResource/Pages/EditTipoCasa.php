<?php

namespace App\Filament\Resources\TipoCasaResource\Pages;

use App\Filament\Resources\TipoCasaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoCasa extends EditRecord
{
    protected static string $resource = TipoCasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
