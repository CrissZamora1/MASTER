<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteEntregaResource\Pages;
use App\Filament\Resources\ReporteEntregaResource\RelationManagers;
use App\Models\ReporteEntrega;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReporteEntregaResource extends Resource
{
    protected static ?string $model = ReporteEntrega::class;
    protected static bool $shouldRegisterNavigation = false;
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
                    ->label('Notas generales del recorrido')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('encargado')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn($record) => static::getUrl('edit', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('entrega.casa.numero_casa')
                    ->label('Casa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('encargado'),

                Tables\Columns\TextColumn::make('tickets_totales')
                    ->label('Tickets')
                    ->getStateUsing(fn($record) => $record->fotos()->count()),

                Tables\Columns\TextColumn::make('tickets_finalizados')
                    ->label('Finalizados')
                    ->getStateUsing(fn($record) => $record->fotos()->where('estado', 'finalizado')->count() . ' / ' . $record->fotos()->count()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Hace')
                    ->getStateUsing(fn($record) => $record->tiempo_transcurrido)
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\FotosRelationManager::class,
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
