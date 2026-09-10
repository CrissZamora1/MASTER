<?php

namespace App\Filament\Resources\CitaResource\Pages;

use App\Filament\Resources\CitaResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCita extends ViewRecord
{
    protected static string $resource = CitaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Datos de la Cita')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('casa.numero_casa')->label('Casa'),
                        TextEntry::make('cliente.nombre')
                            ->label('Cliente')
                            ->formatStateUsing(fn($record) => $record->cliente?->nombre . ' ' . $record->cliente?->apellido),
                        TextEntry::make('cliente.telefono')->label('Teléfono'),
                        TextEntry::make('fecha_hora')->label('Fecha y hora')->dateTime('d/m/Y H:i'),
                        TextEntry::make('estado')
                            ->label('Estado')
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'programada' => 'Agendada',
                                'reprogramada' => 'Reagendada',
                                default => $state,
                            }),
                        TextEntry::make('casa.estado')
                            ->label('Resultado')
                            ->formatStateUsing(fn(?string $state): string => match ($state) {
                                'entregado' => 'Entregado',
                                'no_asistio' => 'No asistió',
                                default => 'Pendiente',
                            }),
                    ]),
            ]);
    }
}
