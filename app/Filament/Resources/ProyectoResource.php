<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
         return $form
        ->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('ubicacion')
                ->maxLength(255),
            Forms\Components\Textarea::make('descripcion')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table
        ->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('ubicacion')
                ->searchable(),
            Tables\Columns\IconColumn::make('activo')
                ->boolean(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
