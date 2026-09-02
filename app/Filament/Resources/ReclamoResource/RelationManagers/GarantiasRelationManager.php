<?php

namespace App\Filament\Resources\ReclamoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class GarantiasRelationManager extends RelationManager
{
    protected static string $relationship = 'garantias';

    protected static ?string $title = 'Garantías';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('garantia_id')
                    ->label('Garantía')
                    ->relationship('garantia', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha inicio (entrega)')
                    ->native(false)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Se calcula automáticamente según la fecha de entrega de la casa.'),

                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Vence')
                    ->native(false)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Se calcula automáticamente: fecha de entrega + duración de esta garantía.'),

                Forms\Components\Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'garantia_aceptada' => 'Garantía aceptada',
                        'fuera_de_garantia' => 'Fuera de garantía',
                    ])
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Se calcula automáticamente según la fecha de vencimiento.'),

                Forms\Components\Toggle::make('validado_manualmente')
                    ->label('Marcar esta garantía como válida (excepción manual)')
                    ->helperText('Fuerza el estado a "Garantía aceptada" sin importar la fecha de vencimiento.')
                    ->visible(fn () => auth()->user()?->esMaster() || auth()->user()?->esSuper()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('garantia.nombre')
                    ->label('Garantía'),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'garantia_aceptada',
                        'danger' => 'fuera_de_garantia',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'garantia_aceptada' => 'Garantía aceptada',
                        'fuera_de_garantia' => 'Fuera de garantía',
                        default => $state,
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
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