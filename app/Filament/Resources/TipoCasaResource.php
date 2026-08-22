<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoCasaResource\Pages;
use App\Filament\Resources\TipoCasaResource\RelationManagers;
use App\Models\TipoCasa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoCasaResource extends Resource
{
    protected static ?string $model = TipoCasa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('proyecto_id')
                ->relationship('proyecto', 'nombre')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('metros')
                ->numeric()
                ->suffix('m²'),
            Forms\Components\Textarea::make('observaciones')
                ->columnSpanFull(),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('proyecto.nombre')
                ->label('Proyecto')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('nombre')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('metros')
                ->suffix(' m²')
                ->sortable(),
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
            'index' => Pages\ListTipoCasas::route('/'),
            'create' => Pages\CreateTipoCasa::route('/create'),
            'edit' => Pages\EditTipoCasa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->esSuper()) {
            $query->whereIn('proyecto_id', $user->proyectosAsignados()->pluck('proyectos.id'));
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', TipoCasa::class) ?? false;
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