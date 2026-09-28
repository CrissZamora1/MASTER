<?php

namespace App\Filament\Resources\EntregaResource\RelationManagers;

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

    protected static ?string $title = 'Tickets de reparación (fotos)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('ruta')
                    ->label('Foto (estado inicial)')
                    ->image()
                    ->disk('public')
                    ->directory('reportes-entrega')
                    ->required(),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción del defecto')
                    ->columnSpanFull(),

                Forms\Components\Select::make('contratista_id')
                    ->label('Contratista encargado')
                    ->relationship('contratista', 'nombre')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('descripcion')
            ->columns([
                Tables\Columns\ImageColumn::make('ruta')
                    ->label('Foto')
                    ->disk('public')
                    ->size(80),

                Tables\Columns\TextColumn::make('descripcion')
                    ->limit(50),

                Tables\Columns\TextColumn::make('contratista.nombre')
                    ->label('Encargado')
                    ->default('—'),

                Tables\Columns\ImageColumn::make('ruta_final')
                    ->label('Foto final')
                    ->disk('public')
                    ->size(80)
                    ->default(null),

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
                Tables\Actions\Action::make('marcar_finalizado')
                    ->label('Marcar finalizado')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado !== 'finalizado' && (
                        auth()->user()?->esMaster()
                        || auth()->user()?->esSuper()
                        || auth()->user()?->esAdmin()
                        || auth()->user()?->esSupervisor()
                    ))
                    ->form([
                        Forms\Components\FileUpload::make('ruta_final')
                            ->label('Foto de cómo quedó terminado')
                            ->image()
                            ->disk('public')
                            ->directory('reportes-entrega-finalizados')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (array $data, $record) {
                        $record->update([
                            'ruta_final' => $data['ruta_final'],
                            'estado' => 'finalizado',
                            'aprobado_por' => auth()->id(),
                            'aprobado_at' => now(),
                        ]);
                    }),

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
                        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $record) {
                            $reclamo = Reclamo::create([
                                'casa_id' => $record->entrega->casa_id,
                                'descripcion' => $record->descripcion,
                                'fecha_reporte' => now(),
                            ]);

                            $ticket = $reclamo->garantias()->create([
                                'garantia_id' => $data['garantia_id'],
                                'contratista_id' => $record->contratista_id,
                            ]);

                            if ($record->ruta) {
                                $ticket->fotos()->create(['ruta' => $record->ruta]);
                            }

                            $record->update(['reclamo_id' => $reclamo->id]);
                        });
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
