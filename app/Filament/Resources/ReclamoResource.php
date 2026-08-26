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
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->options(fn () => Casa::where('estado', 'entregado')
                        ->with(['proyecto', 'tipoCasa'])
                        ->get()
                        ->mapWithKeys(fn ($casa) => [
                            $casa->id => "Casa {$casa->numero_casa} - {$casa->tipoCasa?->nombre} - {$casa->proyecto?->nombre}",
                        ]))
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('garantia_id')
                    ->label('Garantía')
                    ->relationship('garantia', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),

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
                    ->helperText('Se calcula automáticamente: fecha de entrega + duración de la garantía.'),

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
                    ->label('Marcar garantía como válida (excepción manual)')
                    ->helperText('Fuerza el estado a "Garantía aceptada" sin importar la fecha de vencimiento.')
                    ->visible(fn () => auth()->user()?->esMaster() || auth()->user()?->esSuper()),

                Forms\Components\DatePicker::make('fecha_reporte')
                    ->native(false),

                Forms\Components\Textarea::make('descripcion')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('casa_label')
                    ->label('Casa')
                    ->getStateUsing(fn ($record) => "Casa {$record->casa?->numero_casa} - {$record->casa?->tipoCasa?->nombre} - {$record->casa?->proyecto?->nombre}")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('casa', fn ($q) => $q->where('numero_casa', 'like', "%{$search}%"));
                    }),

                Tables\Columns\TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->getStateUsing(fn ($record) => $record->cliente
                        ? $record->cliente->nombre . ' ' . $record->cliente->apellido
                        : 'Sin cliente'),

                Tables\Columns\TextColumn::make('garantia.nombre')
                    ->label('Garantía')
                    ->searchable(),

                Tables\Columns\TextColumn::make('ticket')
                    ->searchable(),

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

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'garantia_aceptada' => 'Garantía aceptada',
                        'fuera_de_garantia' => 'Fuera de garantía',
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->esSuper() && ! $user->esMaster()) {
            $proyectoIds = $user->proyectosAsignados()->pluck('proyectos.id');
            $query->whereHas('casa', fn ($q) => $q->whereIn('proyecto_id', $proyectoIds));
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Reclamo::class) ?? false;
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