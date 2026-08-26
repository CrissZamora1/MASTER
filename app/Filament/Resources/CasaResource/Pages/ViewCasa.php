<?php

namespace App\Filament\Resources\CasaResource\Pages;

use App\Filament\Resources\CasaResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCasa extends ViewRecord
{
    protected static string $resource = CasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Datos generales')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('proyecto.nombre')->label('Proyecto'),
                        TextEntry::make('tipoCasa.nombre')->label('Tipo de casa'),
                        TextEntry::make('numero_casa')->label('N° Casa'),
                        TextEntry::make('cluster')->label('Cluster'),
                        TextEntry::make('anexo')->label('Anexo'),
                        TextEntry::make('estado')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'disponible' => 'Disponible',
                                'no_disponible' => 'No disponible',
                                'programada' => 'Programada',
                                'reprogramada' => 'Reprogramada',
                                'entregado' => 'Entregado',
                                default => $state,
                            }),
                    ]),

                Section::make('Cliente actual')
                    ->schema([
                        TextEntry::make('ultimaEntrega.cliente.nombre')->label('Nombre'),
                        TextEntry::make('ultimaEntrega.cliente.apellido')->label('Apellido'),
                        TextEntry::make('ultimaEntrega.cliente.telefono')->label('Teléfono'),
                        TextEntry::make('ultimaEntrega.cliente.email')->label('Correo'),
                    ])
                    ->columns(4)
                    ->visible(fn ($record) => $record->ultimaEntrega?->cliente !== null),

                Section::make('Cronología de Citas')
                    ->schema([
                        RepeatableEntry::make('citas')
                            ->label('')
                            ->schema([
                                TextEntry::make('fecha_hora')->label('Fecha')->dateTime('d/m/Y H:i'),
                                TextEntry::make('cliente.nombre')->label('Cliente'),
                                TextEntry::make('estado')->badge(),
                            ])
                            ->columns(3),
                    ])
                    ->collapsible(),

                Section::make('Cronología de Entregas')
                    ->schema([
                        RepeatableEntry::make('entregas')
                            ->label('')
                            ->schema([
                                TextEntry::make('fecha_hora_entrega')->label('Fecha')->dateTime('d/m/Y H:i'),
                                TextEntry::make('cliente.nombre')->label('Cliente'),
                                TextEntry::make('resultado')->badge(),
                            ])
                            ->columns(3),
                    ])
                    ->collapsible(),

                Section::make('Reclamos')
                    ->schema([
                        RepeatableEntry::make('reclamos')
                            ->label('')
                            ->schema([
                                TextEntry::make('garantia.nombre')->label('Garantía'),
                                TextEntry::make('fecha_fin')->label('Vence')->date('d/m/Y'),
                                TextEntry::make('estado')->badge(),
                                TextEntry::make('descripcion')->label('Descripción')->columnSpanFull(),
                            ])
                            ->columns(3),
                    ])
                    ->collapsible(),
            ]);
    }
}