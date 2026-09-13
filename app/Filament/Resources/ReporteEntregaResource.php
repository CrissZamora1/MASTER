<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteEntregaResource\Pages;
use App\Models\Garantia;
use App\Models\Reclamo;
use App\Models\ReporteEntrega;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ReclamoResource;

class ReporteEntregaResource extends Resource
{
    protected static ?string $model = ReporteEntrega::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('entrega_id')
                    ->label('Entrega')
                    ->relationship('entrega', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->fecha_hora_entrega->format('d/m/Y H:i') . ' - Casa ' . $record->casa?->numero_casa)
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Textarea::make('descripcion')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('fotos')
                    ->label('Fotos')
                    ->multiple()
                    ->image()
                    ->directory('reportes-entrega')
                    ->reorderable()
                    ->columnSpanFull(),

                Forms\Components\Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'no_terminado' => 'No terminado',
                        'finalizado' => 'Finalizado',
                    ])
                    ->default('pendiente')
                    ->required(),

                Forms\Components\TextInput::make('encargado')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entrega.casa.numero_casa')
                    ->label('Casa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'danger' => 'pendiente',
                        'warning' => 'no_terminado',
                        'success' => 'finalizado',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'no_terminado' => 'No terminado',
                        'finalizado' => 'Finalizado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('encargado'),

                Tables\Columns\IconColumn::make('reclamo_id')
                    ->label('¿Ya es Reclamo?')
                    ->boolean()
                    ->getStateUsing(fn($record) => (bool) $record->reclamo_id),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Hace')
                    ->getStateUsing(fn($record) => $record->tiempo_transcurrido)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'no_terminado' => 'No terminado',
                        'finalizado' => 'Finalizado',
                    ]),
            ])
            ->actions([
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
                    ->action(function (array $data, ReporteEntrega $record) {
                        $reclamo = Reclamo::create([
                            'casa_id' => $record->entrega->casa_id,
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

                Tables\Actions\EditAction::make()
                    ->using(function ($record, array $data) {
                        $fotos = $data['fotos'] ?? [];
                        unset($data['fotos']);

                        $record->update($data);

                        $record->fotos()->delete();
                        foreach ($fotos as $ruta) {
                            $record->fotos()->create(['ruta' => $ruta]);
                        }

                        return $record;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReporteEntregas::route('/'),
            'create' => Pages\CreateReporteEntrega::route('/create'),
            'edit' => Pages\EditReporteEntrega::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', ReporteEntrega::class) ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('update', $record) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('delete', $record) ?? false;
    }
}
