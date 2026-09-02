<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReclamoResource\Pages;
use App\Filament\Resources\ReclamoResource\RelationManagers;
use App\Models\Reclamo;
use App\Models\Casa;
use App\Models\Garantia;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Carbon;

class ReclamoResource extends Resource
{
    protected static ?string $model = Reclamo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Reclamo')
                    ->columns(2)
                    ->schema([
                        Select::make('casa_id')
                            ->relationship('casa', 'n_casa')
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $casa = Casa::find($state);
                                $ultimaEntrega = $casa?->ultimaEntrega;
                                if ($ultimaEntrega) {
                                    $fecha = Carbon::parse($ultimaEntrega->fecha_hora_entrega);
                                    $set('fecha_inicio', $fecha->format('Y-m-d'));
                                }
                            }),

                        Select::make('garantia_id')
                            ->relationship('garantia', 'nombre')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $fechaInicio = $get('fecha_inicio');
                                $garantia = Garantia::find($state);
                                if ($fechaInicio && $garantia) {
                                    $vence = Carbon::parse($fechaInicio)->addMonths((int)$garantia->meses_duracion);
                                    $set('fecha_fin', $vence->format('Y-m-d'));
                                    $estado = now()->startOfDay()->gt($vence) ? 'fuera_de_garantia' : 'pendiente';
                                    $set('estado', $estado);
                                }
                            }),

                        DatePicker::make('fecha_inicio')
                            ->label('Fecha inicio (entrega)')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->readonly(),

                        DatePicker::make('fecha_fin')
                            ->label('Vence')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->readonly(),

                        Select::make('estado')
                            ->options([
                                'pendiente' => 'Pendiente (En Garantía)',
                                'garantia_aceptada' => 'Garantía Aceptada (Manual)',
                                'fuera_de_garantia' => 'Fuera de Garantía',
                                'finalizado' => 'Finalizado',
                            ])
                            ->native(false)
                            ->disabled()
                            ->dehydrated(),

                        Toggle::make('validado_manualmente')
                            ->label('Marcar garantía como válida (excepción manual)')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) $set('estado', 'garantia_aceptada');
                            }),
                    ]),

                Section::make('Asignación de Ticket e Informe Inicial')
                    ->columns(2)
                    ->schema([
                        Select::make('contratista_id')
                            ->label('Asignar Contratista')
                            // Filtramos usuarios que tengan rol de contratista
                            ->options(User::whereHas('roles', fn($q) => $q->where('name', 'CONT'))->pluck('name', 'id'))
                            ->required(),

                        TextInput::make('ticket')
                            ->default(fn () => 'TK-' . strtoupper(uniqid()))
                            ->readonly(),

                        Textarea::make('descripcion')
                            ->label('Descripción del problema')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('casa.n_casa')->label('Casa')->sortable(),
                Tables\Columns\TextColumn::make('ticket')->label('Ticket')->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'garantia_aceptada' => 'success',
                        'fuera_de_garantia' => 'danger',
                        'finalizado' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('fecha_fin')->label('Vence')->date('d/m/Y'),
            ])
            ->filters([
                //
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
}
