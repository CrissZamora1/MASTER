<?php

namespace App\Filament\Resources\ReporteEntregaResource\RelationManagers;

use App\Filament\Resources\ReclamoResource;
use App\Models\Garantia;
use App\Models\Reclamo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FotosRelationManager extends RelationManager
{
    protected static string $relationship = 'fotos';

    protected static ?string $title = 'Tickets (fotos del recorrido)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('ruta')
                    ->label('Foto')
                    ->image()
                    ->directory('reportes-entrega')
                    ->required(),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción del defecto')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('descripcion')
            ->columns([
                Tables\Columns\ImageColumn::make('ruta')
                    ->label('Foto')
                    ->size(80),

                Tables\Columns\TextColumn::make('descripcion')
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'danger' => 'pendiente',
                        'success' => 'finalizado',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'finalizado' => 'Finalizado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('aprobadoPor.name')
                    ->label('Visto bueno de')
                    ->default('—'),

                Tables\Columns\IconColumn::make('reclamo_id')
                    ->label('¿Es Reclamo?')
                    ->boolean()
                    ->getStateUsing(fn($record) => (bool) $record->reclamo_id),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('visto_bueno')
                    ->label('Marcar visto bueno')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn($record) => $record->estado !== 'finalizado' && (
                        auth()->user()?->esMaster()
                        || auth()->user()?->esSuper()
                        || auth()->user()?->esAdmin()
                        || auth()->user()?->esSupervisor()
                    ))
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->marcarVistoBueno()),

                Tables\Actions\Action::make('convertir_a_reclamo')
                    ->label('Convertir a Reclamo')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn($record) => ! $record->reclamo_id)
                    ->form([
                        Forms\Components\Select::make('garantia_id')
                            ->label('Garantía del defecto')
                            ->options(Garantia::pluck('nombre', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (array $data, $record) {
                        $reclamo = Reclamo::create([
                            'casa_id' => $record->reporte->entrega->casa_id,
                            'descripcion' => $record->descripcion,
                            'fecha_reporte' => now(),
                        ]);

                        $reclamo->garantias()->create([
                            'garantia_id' => $data['garantia_id'],
                        ]);

                        $record->update(['reclamo_id' => $reclamo->id]);
                    }),

                Tables\Actions\Action::make('ver_reclamo')
                    ->label('Ver Reclamo')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->visible(fn($record) => (bool) $record->reclamo_id)
                    ->url(fn($record) => ReclamoResource::getUrl('edit', ['record' => $record->reclamo_id])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
