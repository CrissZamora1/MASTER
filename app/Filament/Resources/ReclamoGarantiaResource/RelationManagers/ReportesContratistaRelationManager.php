<?php

namespace App\Filament\Resources\ReclamoGarantiaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportesContratistaRelationManager extends RelationManager
{
    protected static string $relationship = 'reportes';

    protected static ?string $title = 'Notas del Contratista';

    public function isReadOnly(): bool
    {
        return !auth()->user()->esContratista();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('descripcion')
                ->columnSpanFull(),

            Forms\Components\Select::make('estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'en_proceso' => 'En proceso',
                    'finalizado' => 'Finalizado',
                ])
                ->default('pendiente')
                ->required(),

            Forms\Components\FileUpload::make('fotos')
                ->label('Fotos')
                ->multiple()
                ->image()
                ->directory('reportes-reclamos')
                ->reorderable()
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                $query->whereHas('creadoPor.rol', fn($q) => $q->where('codigo', 'CONT'));

                if ($user->esContratista()) {
                    $proyectoId = $this->getOwnerRecord()->reclamo?->casa?->proyecto_id;
                    if (! $proyectoId || ! $user->tieneAccesoAProyecto($proyectoId)) {
                        $query->whereRaw('1 = 0'); // por seguridad
                    }
                }

                return $query;
            })
            ->recordTitleAttribute('descripcion')
            ->columns([
                Tables\Columns\TextColumn::make('creadoPor.name')->label('Contratista'),
                Tables\Columns\TextColumn::make('descripcion')->limit(60),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors(['danger' => 'pendiente', 'warning' => 'en_proceso', 'success' => 'finalizado'])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En proceso',
                        'finalizado' => 'Finalizado',
                        default => $state,
                    }),
                Tables\Columns\ImageColumn::make('fotos.ruta')->label('Fotos')->circular()->stacked(),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model) {
                        $fotos = $data['fotos'] ?? [];
                        unset($data['fotos']);

                        $owner = $this->getOwnerRecord();

                        $reporte = $model::create($data + [
                            'reclamo_garantia_id' => $owner->id,
                            'contratista_id' => $owner->contratista_id,
                        ]);

                        foreach ($fotos as $ruta) {
                            $reporte->fotos()->create(['ruta' => $ruta]);
                        }

                        return $reporte;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, $record): array {
                        $data['fotos'] = $record->fotos()->pluck('ruta')->all();

                        return $data;
                    })
                    ->using(function ($record, array $data) {
                        $fotos = array_values($data['fotos'] ?? []);
                        unset($data['fotos']);

                        $record->update($data);

                        $actuales = $record->fotos()->pluck('ruta')->all();

                        $record->fotos()->whereNotIn('ruta', $fotos)->delete();

                        foreach (array_diff($fotos, $actuales) as $ruta) {
                            $record->fotos()->create(['ruta' => $ruta]);
                        }

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
