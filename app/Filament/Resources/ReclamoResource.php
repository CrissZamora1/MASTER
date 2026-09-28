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
use Filament\Infolists;
use Filament\Infolists\Infolist;


class ReclamoResource extends Resource
{
    protected static ?string $model = Reclamo::class;

    protected static ?string $navigationGroup = 'Gestión';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Reclamos';

    protected static ?string $modelLabel = 'reclamo';

    protected static ?string $pluralModelLabel = 'reclamos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('casa_id')
                    ->label('Casa')
                    ->relationship('casa', 'numero_casa', fn (Builder $query) => $query->visiblePara(auth()->user()))
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('ticket')
                    ->default(fn() => 'TK-' . strtoupper(uniqid()))
                    ->readonly(),

                Forms\Components\DatePicker::make('fecha_reporte')
                    ->native(false),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción del problema')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Información del reclamo')
                ->schema([
                    Infolists\Components\TextEntry::make('casa.numero_casa')->label('Casa'),
                    Infolists\Components\TextEntry::make('ticket'),
                    Infolists\Components\TextEntry::make('fecha_reporte')->date('d/m/Y'),
                    Infolists\Components\TextEntry::make('descripcion')->columnSpanFull(),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Garantías relacionadas')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('garantias')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('garantia.nombre')->label('Garantía'),
                            Infolists\Components\TextEntry::make('estado'),
                            Infolists\Components\TextEntry::make('fecha_fin')->label('Vence')->date('d/m/Y'),
                            Infolists\Components\TextEntry::make('id')
                                ->label('Ver detalle')
                                ->url(fn($record) => route('filament.admin.resources.reclamo-garantias.view', $record))
                                ->color('primary'),
                        ])
                        ->columns(4),
                ]),
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
                    ->getStateUsing(fn($record) => $record->garantias->pluck('garantia.nombre')->filter()->join(', ') ?: 'Sin garantías'),

                Tables\Columns\TextColumn::make('proximo_vencimiento')
                    ->label('Próximo vence')
                    ->getStateUsing(fn($record) => optional($record->garantias->sortBy('fecha_fin')->first())->fecha_fin?->format('d/m/Y') ?? '—'),

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
                            fn(Builder $q, $value) => $q->whereHas('garantias', fn($q2) => $q2->where('estado', $value))
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReclamos::route('/'),
            'create' => Pages\CreateReclamo::route('/create'),
            'view' => Pages\ViewReclamo::route('/{record}'),
            'edit' => Pages\EditReclamo::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (!$user || $user->esMaster() || $user->esSuper()) {
            return $query;
        }

        return $query->whereHas('casa', function ($q) use ($user) {
            $q->whereIn('proyecto_id', $user->proyectosAsignados()->pluck('proyectos.id'));
        });
    }
}
