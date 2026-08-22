<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CasaResource\Pages;
use App\Filament\Resources\CasaResource\RelationManagers;
use App\Models\Casa;
use App\Models\TipoCasa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CasaResource extends Resource
{
    protected static ?string $model = Casa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('proyecto_id')
                    ->relationship('proyecto', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('tipo_casa_id', null)),

                Forms\Components\Select::make('tipo_casa_id')
                    ->label('Tipo de Casa')
                    ->options(fn (Forms\Get $get) => TipoCasa::where('proyecto_id', $get('proyecto_id'))->pluck('nombre', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('numero_casa')
                    ->label('Número de casa')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('cluster')
                    ->maxLength(255),

                Forms\Components\TextInput::make('anexo')
                    ->maxLength(255),

                Forms\Components\Toggle::make('acabados')
                    ->label('Entregable?'),

                Forms\Components\Select::make('estado')
                    ->label('Estado inicial')
                    ->options([
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No disponible',
                    ])
                    ->default('no_disponible')
                    ->required()
                    ->visible(fn (string $operation): bool => $operation === 'create')
                    ->helperText('Una vez que la casa tenga citas o entregas, el estado se actualizará automáticamente.'),

                Forms\Components\Placeholder::make('estado_actual')
                    ->label('Estado actual')
                    ->content(fn ($record) => $record ? match ($record->estado) {
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No disponible',
                        'programada' => 'Programada',
                        'reprogramada' => 'Reprogramada',
                        'entregado' => 'Entregado',
                        default => $record->estado,
                    } : '-')
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proyecto.nombre')
                    ->label('Proyecto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipoCasa.nombre')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('numero_casa')
                    ->label('N° Casa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cluster')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'disponible',
                        'danger' => 'no_disponible',
                        'warning' => fn ($state) => in_array($state, ['programada', 'reprogramada']),
                        'primary' => 'entregado',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No disponible',
                        'programada' => 'Programada',
                        'reprogramada' => 'Reprogramada',
                        'entregado' => 'Entregado',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('acabados')
                    ->label('Entregable?')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No disponible',
                        'programada' => 'Programada',
                        'reprogramada' => 'Reprogramada',
                        'entregado' => 'Entregado',
                    ]),

                Tables\Filters\SelectFilter::make('proyecto_id')
                    ->label('Proyecto')
                    ->relationship('proyecto', 'nombre'),
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
            'index' => Pages\ListCasas::route('/'),
            'create' => Pages\CreateCasa::route('/create'),
            'edit' => Pages\EditCasa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->esSuper()) {
            $query->whereIn('proyecto_id', $user->proyectosAsignados()->pluck('proyectos.id'));
        }

        return $query;
    }

    public static function canCreate(): bool
    {
            return auth()->user()?->can('create', Casa::class) ?? false;
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