<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReclamoResource\Pages;
use App\Filament\Resources\ReclamoResource\RelationManagers;
use App\Models\Casa;
use App\Models\Reclamo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReclamoResource extends Resource
{
    protected static ?string $model = Reclamo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('casa_id')
                    ->label('Casa')
                    ->relationship('casa', 'numero_casa')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('ticket')
                    ->default(fn () => 'TK-'.strtoupper(uniqid()))
                    ->readonly(),

                Forms\Components\DatePicker::make('fecha_reporte')
                    ->native(false),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción del problema')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('casa.numero_casa')
                    ->label('Casa')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ticket')
                    ->searchable(),

                Tables\Columns\TextColumn::make('garantias_lista')
                    ->label('Garantías')
                    ->getStateUsing(fn ($record) => $record->garantias->pluck('garantia.nombre')->filter()->join(', ') ?: 'Sin garantías'),

                Tables\Columns\TextColumn::make('proximo_vencimiento')
                    ->label('Próximo vence')
                    ->getStateUsing(fn ($record) => optional($record->garantias->sortBy('fecha_fin')->first())->fecha_fin?->format('d/m/Y') ?? '—'),

                Tables\Columns\TextColumn::make('fecha_reporte')
                    ->label('Reportado')
                    ->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado de alguna garantía')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'garantia_aceptada' => 'Garantía aceptada',
                        'fuera_de_garantia' => 'Fuera de garantía',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $q, $value) => $q->whereHas('garantias', fn ($q2) => $q2->where('estado', $value))
                        );
                    }),
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
            RelationManagers\GarantiasRelationManager::class,
            RelationManagers\ReportesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReclamos::route('/'),
            'create' => Pages\CreateReclamo::route('/create'),
            'edit' => Pages\EditReclamo::route('/{record}/edit'),
        ];
    }
}