<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContratistaResource\Pages;
use App\Filament\Resources\ContratistaResource\RelationManagers;
use App\Models\Contratista;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContratistaResource extends Resource
{
    protected static ?string $model = Contratista::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('especialidad')
                    ->maxLength(255),

                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),

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

            Tables\Columns\TextColumn::make('especialidad')
                ->searchable(),

            Tables\Columns\TextColumn::make('telefono'),

            Tables\Columns\TextColumn::make('email')
                ->searchable(),

            Tables\Columns\IconColumn::make('activo')
                ->boolean(),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('activo'),
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
            'index' => Pages\ListContratistas::route('/'),
            'create' => Pages\CreateContratista::route('/create'),
            'edit' => Pages\EditContratista::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Contratista::class) ?? false;
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