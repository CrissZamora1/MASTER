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
            Actions\Action::make('crear-por-rangos')
                ->label('Crear por rangos')
                ->icon('heroicon-o-squares-plus')
                ->url(fn () => static::getResource()::getUrl('crear-por-rangos')),
            Actions\CreateAction::make(),
        ];
    }
}