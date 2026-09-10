<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitaResource\Pages;
use App\Filament\Resources\EntregaResource;
use App\Models\Casa;
use App\Models\Cita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CitaResource extends Resource
{
    protected static ?string $model = Cita::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('casa_id')
                    ->label('Casa')
                    ->options(function (?Cita $record) {
                        return Casa::query()
                            ->where(function ($q) use ($record) {
                                $q->where('estado', 'disponible');
                                if ($record?->casa_id) {
                                    $q->orWhere('id', $record->casa_id);
                                }
                            })
                            ->with(['proyecto', 'tipoCasa'])
                            ->get()
                            ->mapWithKeys(fn($casa) => [
                                $casa->id => "Casa {$casa->numero_casa} - {$casa->tipoCasa?->nombre} - {$casa->proyecto?->nombre}",
                            ]);
                    })
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')->required(),
                        Forms\Components\TextInput::make('apellido')->required(),
                        Forms\Components\TextInput::make('dpi'),
                        Forms\Components\TextInput::make('telefono')->tel(),
                        Forms\Components\TextInput::make('email')->email(),
                    ]),

                Forms\Components\DateTimePicker::make('fecha_hora')
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn($record) => static::getUrl('view', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('casa.numero_casa')
                    ->label('Casa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_hora')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Tipo')
                    ->colors([
                        'warning' => 'programada',
                        'info' => 'reprogramada',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'programada' => 'Agendada',
                        'reprogramada' => 'Reagendada',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('casa.estado')
                    ->label('Resultado')
                    ->colors([
                        'success' => 'entregado',
                        'danger' => 'no_asistio',
                        'gray' => fn($state) => in_array($state, ['programada', 'reprogramada', 'disponible']),
                    ])
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'entregado' => 'Entregado',
                        'no_asistio' => 'No asistió',
                        default => 'Pendiente',
                    }),

                Tables\Columns\IconColumn::make('bloqueada')
                    ->label('Bloqueada')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->bloqueada),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'programada' => 'Agendada',
                        'reprogramada' => 'Reagendada',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('ver_entrega')
                    ->label('Ver Entrega')
                    ->icon('heroicon-o-eye')
                    ->visible(fn($record) => $record->entrega !== null)
                    ->url(fn($record) => EntregaResource::getUrl('edit', ['record' => $record->entrega?->id])),

                Tables\Actions\Action::make('reagendar')
                    ->label('Reagendar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn($record) => $record->casa?->estado === 'no_asistio')
                    ->form([
                        Forms\Components\Select::make('cliente_id')
                            ->label('Cliente')
                            ->relationship('cliente', 'nombre')
                            ->default(fn($record) => $record->cliente_id)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')->required(),
                                Forms\Components\TextInput::make('apellido')->required(),
                                Forms\Components\TextInput::make('dpi'),
                                Forms\Components\TextInput::make('telefono')->tel(),
                                Forms\Components\TextInput::make('email')->email(),
                            ]),

                        Forms\Components\DateTimePicker::make('fecha_hora')
                            ->label('Nueva fecha y hora')
                            ->required()
                            ->native(false),
                    ])
                    ->action(function (array $data, Cita $record) {
                        Cita::create([
                            'casa_id' => $record->casa_id,
                            'cliente_id' => $data['cliente_id'],
                            'fecha_hora' => $data['fecha_hora'],
                            'cita_previa_id' => $record->id,
                        ]);
                    }),

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
            'index' => Pages\ListCitas::route('/'),
            'create' => Pages\CreateCita::route('/create'),
            'view' => Pages\ViewCita::route('/{record}'),
            'edit' => Pages\EditCita::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->esSuper() && ! $user->esMaster()) {
            $proyectoIds = $user->proyectosAsignados()->pluck('proyectos.id');
            $query->whereHas('casa', fn($q) => $q->whereIn('proyecto_id', $proyectoIds));
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Cita::class) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('delete', $record) ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('update', $record) ?? false;
    }
}
