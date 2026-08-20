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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->fecha_hora_entrega->format('d/m/Y H:i') . ' - Casa ' . $record->casa?->numero_casa)
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\Textarea::make('descripcion')
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('foto')
                ->image()
                ->directory('reportes-entrega'),

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
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pendiente' => 'Pendiente',
                    'no_terminado' => 'No terminado',
                    'finalizado' => 'Finalizado',
                    default => $state,
                }),

            Tables\Columns\TextColumn::make('encargado'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Hace')
                ->getStateUsing(fn ($record) => $record->tiempo_transcurrido)
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
}
