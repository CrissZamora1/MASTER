<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GarantiaResource\Pages;
use App\Filament\Resources\GarantiaResource\RelationManagers;
use App\Models\Garantia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GarantiaResource extends Resource
{
    protected static ?string $model = Garantia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
    return $form
        ->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('meses_duracion')
                ->label('Meses de duración')
                ->numeric()
                ->required()
                ->suffix('meses'),

            Forms\Components\Textarea::make('descripcion')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('meses_duracion')
                ->label('Duración')
                ->suffix(' meses')
                ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGarantias::route('/'),
            'create' => Pages\CreateGarantia::route('/create'),
            'edit' => Pages\EditGarantia::route('/{record}/edit'),
        ];
    }
}
