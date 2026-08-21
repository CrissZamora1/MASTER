<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitaResource\Pages;
use App\Filament\Resources\CitaResource\RelationManagers;
use App\Models\Cita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CitaResource extends Resource
{
    protected static ?string $model = Cita::class;

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

            Forms\Components\Select::make('cliente_id')
                ->label('Cliente')
                ->relationship('cliente', 'nombre')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('tipo_cita')
                ->label('Tipo de cita')
                ->maxLength(255),

            Forms\Components\DateTimePicker::make('fecha_hora')
                ->required()
                ->native(false),

            Forms\Components\Select::make('estado')
                ->options([
                    'programada' => 'Programada',
                    'reprogramada' => 'Reprogramada',
                ])
                ->default('programada')
                ->required(),
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

            Tables\Columns\TextColumn::make('tipo_cita')
                ->label('Tipo'),

            Tables\Columns\TextColumn::make('fecha_hora')
                ->label('Fecha y hora')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            Tables\Columns\BadgeColumn::make('estado')
                ->colors([
                    'warning' => 'programada',
                    'danger' => 'reprogramada',
                ])
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'programada' => 'Programada',
                    'reprogramada' => 'Reprogramada',
                    default => $state,
                }),

            Tables\Columns\IconColumn::make('bloqueada')
                ->label('Bloqueada')
                ->boolean()
                ->getStateUsing(fn ($record) => $record->bloqueada),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('estado')
                ->options([
                    'programada' => 'Programada',
                    'reprogramada' => 'Reprogramada',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->disabled(fn ($record) => $record->bloqueada),
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
            'index' => Pages\ListCitas::route('/'),
            'create' => Pages\CreateCita::route('/create'),
            'edit' => Pages\EditCita::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();
    $user = auth()->user();

    if ($user && ! $user->esSuper()) {
        $proyectoIds = $user->proyectosAsignados()->pluck('proyectos.id');
        $query->whereHas('casa', fn ($q) => $q->whereIn('proyecto_id', $proyectoIds));
    }

    return $query;
}
}
