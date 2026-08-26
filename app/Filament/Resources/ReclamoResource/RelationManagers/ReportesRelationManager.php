<?php

namespace App\Filament\Resources\ReclamoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportesRelationManager extends RelationManager
{
    protected static string $relationship = 'reportes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('contratista_id')
                    ->label('Contratista')
                    ->relationship('contratista', 'nombre')
                    ->searchable()
                    ->preload(),

                Forms\Components\Textarea::make('descripcion')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('reportes-reclamos'),

                Forms\Components\Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En proceso',
                        'finalizado' => 'Finalizado',
                    ])
                    ->default('pendiente')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('descripcion')
            ->columns([
                Tables\Columns\TextColumn::make('creadoPor.name')
                    ->label('Creado por')
                    ->badge()
                    ->color(fn ($record) => $record->esDeSupervisor() ? 'info' : ($record->esDeContratista() ? 'warning' : 'gray'))
                    ->formatStateUsing(function ($record) {
                        if ($record->esDeSupervisor()) {
                            return 'Supervisor: '.$record->creadoPor?->name;
                        }
                        if ($record->esDeContratista()) {
                            return 'Contratista: '.$record->creadoPor?->name;
                        }
                        return $record->creadoPor?->name ?? '—';
                    }),

                Tables\Columns\TextColumn::make('contratista.nombre')
                    ->label('Contratista asignado'),

                Tables\Columns\TextColumn::make('descripcion')
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'danger' => 'pendiente',
                        'warning' => 'en_proceso',
                        'success' => 'finalizado',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En proceso',
                        'finalizado' => 'Finalizado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\Filter::make('de_supervisor')
                    ->label('Solo Supervisor')
                    ->query(fn (Builder $query) => $query->whereHas('creadoPor.rol', fn ($q) => $q->where('codigo', 'SUP'))),

                Tables\Filters\Filter::make('de_contratista')
                    ->label('Solo Contratista')
                    ->query(fn (Builder $query) => $query->whereHas('creadoPor.rol', fn ($q) => $q->where('codigo', 'CONT'))),
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