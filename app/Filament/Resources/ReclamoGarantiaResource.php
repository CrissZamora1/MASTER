<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReclamoGarantiaResource\Pages;
use App\Filament\Resources\ReclamoGarantiaResource\RelationManagers;
use App\Models\ReclamoGarantia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReclamoGarantiaResource extends Resource
{
    protected static ?string $model = ReclamoGarantia::class;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->esContratista() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('garantia_id')
                    ->label('Garantía')
                    ->relationship('garantia', 'nombre')
                    ->disabled(),

                Forms\Components\Select::make('contratista_id')
                    ->label('Contratista asignado')
                    ->relationship('contratista', 'nombre')
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('supervisor_id')
                    ->label('Supervisor asignado')
                    ->options(fn() => \App\Models\User::whereHas('rol', fn($q) => $q->where('codigo', 'SUP'))->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReportesContratistaRelationManager::class,
            RelationManagers\ReportesSupervisorRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReclamoGarantias::route('/'),
            'view' => Pages\ViewReclamoGarantia::route('/{record}'),
        ];
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->esContratista()) {
            $query->where('contratista_id', $user->contratista?->id);
        }

        return $query;
    }
}
