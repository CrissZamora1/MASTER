<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntregaResource\Pages;
use App\Filament\Resources\EntregaResource\RelationManagers;
use App\Models\Entrega;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntregaResource extends Resource
{
    protected static ?string $model = Entrega::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('cita_id')
                ->label('Cita')
                ->relationship('cita', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->fecha_hora->format('d/m/Y H:i') . ' - Casa ' . $record->casa?->numero_casa . ' - ' . $record->cliente?->nombre)
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function (Forms\Set $set, $state) {
                    if ($state) {
                        $cita = \App\Models\Cita::find($state);
                        $set('casa_id', $cita?->casa_id);
                        $set('cliente_id', $cita?->cliente_id);
                    }
                }),

            Forms\Components\Select::make('casa_id')
                ->label('Casa')
                ->relationship('casa', 'numero_casa')
                ->required()
                ->searchable()
                ->preload()
                ->disabled(fn (Forms\Get $get) => filled($get('cita_id')))
                ->dehydrated(),

            Forms\Components\Select::make('cliente_id')
                ->label('Cliente')
                ->relationship('cliente', 'nombre')
                ->required()
                ->searchable()
                ->preload()
                ->disabled(fn (Forms\Get $get) => filled($get('cita_id')))
                ->dehydrated(),

            Forms\Components\DateTimePicker::make('fecha_hora_entrega')
                ->required()
                ->native(false)
                ->suffixAction(
                    Forms\Components\Actions\Action::make('hoy')
                        ->label('Hoy')
                        ->icon('heroicon-m-clock')
                        ->action(fn (Forms\Set $set) => $set('fecha_hora_entrega', now()))
                ),

            Forms\Components\Select::make('resultado')
                ->options([
                    'entregada' => 'Entregada',
                    'entregada_con_reclamos' => 'Entregada con reclamos',
                    'no_entregada' => 'No entregada',
                ])
                ->required(),

            Forms\Components\Textarea::make('observaciones')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('casa.numero_casa')
                ->label('Casa')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('cliente.nombre')
                ->label('Cliente')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('fecha_hora_entrega')
                ->label('Fecha de entrega')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            Tables\Columns\BadgeColumn::make('resultado')
                ->colors([
                    'success' => 'entregada',
                    'warning' => 'entregada_con_reclamos',
                    'danger' => 'no_entregada',
                ])
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'entregada' => 'Entregada',
                    'entregada_con_reclamos' => 'Entregada con reclamos',
                    'no_entregada' => 'No entregada',
                    default => '-',
                }),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('resultado')
                ->options([
                    'entregada' => 'Entregada',
                    'entregada_con_reclamos' => 'Entregada con reclamos',
                    'no_entregada' => 'No entregada',
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
            'index' => Pages\ListEntregas::route('/'),
            'create' => Pages\CreateEntrega::route('/create'),
            'edit' => Pages\EditEntrega::route('/{record}/edit'),
        ];
    }
}
