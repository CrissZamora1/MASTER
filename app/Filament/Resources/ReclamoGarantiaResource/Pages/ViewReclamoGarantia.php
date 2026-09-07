<?php

namespace App\Filament\Resources\ReclamoGarantiaResource\Pages;

use App\Filament\Resources\ReclamoGarantiaResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewReclamoGarantia extends ViewRecord
{
    protected static string $resource = ReclamoGarantiaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información del Ticket')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('garantia.nombre')->label('Garantía'),
                        TextEntry::make('contratista.nombre')->label('Contratista asignado')->default('Sin asignar'),
                        TextEntry::make('supervisor.name')->label('Supervisor asignado')->default('Sin asignar'),
                        TextEntry::make('fecha_fin')->label('Vence')->date('d/m/Y'),
                        TextEntry::make('estado')
                            ->label('Garantía')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pendiente' => 'En garantía',
                                'garantia_aceptada' => 'Garantía aceptada (forzada)',
                                'fuera_de_garantia' => 'Fuera de garantía',
                                default => $state,
                            }),
                        TextEntry::make('estado_reparacion')
                            ->label('Reparación')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pendiente' => 'No iniciado',
                                'en_proceso' => 'En proceso',
                                'finalizada' => 'Finalizada',
                                default => $state,
                            }),
                    ]),

                Section::make('Fotos (estado inicial)')
                    ->schema([
                        RepeatableEntry::make('fotos')
                            ->label('')
                            ->schema([
                                TextEntry::make('ruta')
                                    ->label('')
                                    ->html()
                                    ->formatStateUsing(fn ($state) => new \Illuminate\Support\HtmlString(
                                        '<a href="'.\Illuminate\Support\Facades\Storage::url($state).'" target="_blank">
                                            <img src="'.\Illuminate\Support\Facades\Storage::url($state).'" style="width:150px;height:150px;object-fit:cover;border-radius:8px;cursor:pointer;">
                                        </a>'
                                    )),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}