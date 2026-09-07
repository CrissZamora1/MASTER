<?php

namespace App\Filament\Resources\ReclamoResource\RelationManagers;

use App\Filament\Resources\ReclamoGarantiaResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class GarantiasRelationManager extends RelationManager
{
    protected static string $relationship = 'garantias';

    protected static ?string $title = 'Garantías / Tickets';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('garantia_id')
                    ->label('Garantía')
                    ->relationship('garantia', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $garantia = \App\Models\Garantia::find($state);
                        $ultimaEntrega = $this->getOwnerRecord()->casa?->ultimaEntrega;

                        if (! $ultimaEntrega || ! in_array($ultimaEntrega->resultado, ['entregada', 'entregada_con_reclamos'])) {
                            return;
                        }

                        $fechaInicio = $ultimaEntrega->fecha_hora_entrega->copy()->startOfDay();
                        $set('fecha_inicio', $fechaInicio->format('Y-m-d'));

                        if ($garantia) {
                            $vence = $fechaInicio->copy()->addMonths((int) $garantia->meses_duracion);
                            $set('fecha_fin', $vence->format('Y-m-d'));
                            $set('estado', now()->startOfDay()->gt($vence) ? 'fuera_de_garantia' : 'pendiente');
                        }
                    }),

                Forms\Components\Select::make('contratista_id')
                    ->label('Contratista asignado')
                    ->relationship('contratista', 'nombre')
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('supervisor_id')
                    ->label('Supervisor asignado')
                    ->options(fn () => \App\Models\User::whereHas('rol', fn ($q) => $q->where('codigo', 'SUP'))->pluck('name', 'id'))
                    ->searchable(),

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
                        'pendiente' => 'En garantía',
                        'garantia_aceptada' => 'Garantía aceptada (forzada)',
                        'fuera_de_garantia' => 'Fuera de garantía',
                    ])
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Se calcula automáticamente según la fecha de vencimiento.'),

                Forms\Components\FileUpload::make('fotos')
                    ->label('Fotos (estado inicial)')
                    ->multiple()
                    ->image()
                    ->directory('reclamo-garantia-fotos')
                    ->reorderable()
                    ->columnSpanFull(),

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
            ->recordUrl(fn ($record) => ReclamoGarantiaResource::getUrl('view', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('garantia.nombre')
                    ->label('Garantía'),

                Tables\Columns\TextColumn::make('contratista.nombre')
                    ->label('Contratista')
                    ->default('Sin asignar'),

                Tables\Columns\TextColumn::make('supervisor.name')
                    ->label('Supervisor')
                    ->default('Sin asignar'),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Vence')
                    ->date('d/m/Y'),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Garantía')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'garantia_aceptada',
                        'danger' => 'fuera_de_garantia',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'En garantía',
                        'garantia_aceptada' => 'Garantía aceptada (forzada)',
                        'fuera_de_garantia' => 'Fuera de garantía',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('estado_reparacion')
                    ->label('Reparación')
                    ->colors([
                        'danger' => 'pendiente',
                        'warning' => 'en_proceso',
                        'success' => 'finalizada',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'No iniciado',
                        'en_proceso' => 'En proceso',
                        'finalizada' => 'Finalizada',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('ultima_fecha_trabajo')
                    ->label('Último trabajo')
                    ->getStateUsing(function ($record) {
                        $ultimo = $record->reportes()
                            ->whereHas('creadoPor.rol', fn ($q) => $q->where('codigo', 'CONT'))
                            ->latest()
                            ->first();

                        return $ultimo?->created_at?->format('d/m/Y H:i') ?? '—';
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model) {
                        $fotos = $data['fotos'] ?? [];
                        unset($data['fotos']);

                        $ticket = $this->getOwnerRecord()->garantias()->create($data);

                        foreach ($fotos as $ruta) {
                            $ticket->fotos()->create(['ruta' => $ruta]);
                        }

                        return $ticket;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('ver_reportes')
                    ->label('Ver Reportes')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn ($record) => ReclamoGarantiaResource::getUrl('view', ['record' => $record])),

                Tables\Actions\EditAction::make()
                    ->using(function ($record, array $data) {
                        $fotos = $data['fotos'] ?? [];
                        unset($data['fotos']);

                        $record->update($data);

                        $record->fotos()->delete();
                        foreach ($fotos as $ruta) {
                            $record->fotos()->create(['ruta' => $ruta]);
                        }

                        return $record;
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}