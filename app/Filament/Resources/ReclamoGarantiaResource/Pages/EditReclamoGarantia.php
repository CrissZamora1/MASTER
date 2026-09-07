<?php

namespace App\Filament\Resources\ReclamoGarantiaResource\Pages;
use App\Filament\Resources\ReclamoResource;
use App\Filament\Resources\ReclamoGarantiaResource;
use Filament\Resources\Pages\EditRecord;

class EditReclamoGarantia extends EditRecord
{
    protected static string $resource = ReclamoGarantiaResource::class;

    protected function getRedirectUrl(): string
    {
        return ReclamoResource::getUrl('edit', ['record' => $this->getRecord()->reclamo_id]);
    }
}