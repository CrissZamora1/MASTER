<?php

namespace App\Filament\Resources\CasaResource\Pages;

use App\Filament\Resources\CasaResource;
use App\Models\Casa;
use App\Models\Proyecto;
use App\Models\TipoCasa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Page;

class CrearCasasPorRangos extends Page
{
    use InteractsWithFormActions;

    protected static string $resource = CasaResource::class;

    protected static string $view = 'filament.resources.casa-resource.pages.crear-casas-por-rangos';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('proyecto_id')
                    ->label('Proyecto')
                    ->options(Proyecto::pluck('nombre', 'id'))
                    ->required()
                    ->live()
                    ->searchable(),

                Forms\Components\Repeater::make('rangos')
                    ->label('Rangos de casas')
                    ->schema([
                        Forms\Components\TextInput::make('desde')
                            ->label('Desde')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('hasta')
                            ->label('Hasta')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('tipo_casa_id')
                            ->label('Tipo de casa')
                            ->options(fn (Forms\Get $get) => TipoCasa::where('proyecto_id', $get('../../proyecto_id'))->pluck('nombre', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('cluster')
                            ->label('Cluster')
                            ->maxLength(255),
                    ])
                    ->columns(4)
                    ->addActionLabel('Agregar otro rango')
                    ->minItems(1),
            ])
            ->statePath('data');
    }

    public function crear(): void
    {
        $state = $this->form->getState();

        $creadas = 0;
        $omitidas = 0;

        foreach ($state['rangos'] as $rango) {
            for ($num = (int) $rango['desde']; $num <= (int) $rango['hasta']; $num++) {
                $existe = Casa::where('proyecto_id', $state['proyecto_id'])
                    ->where('numero_casa', (string) $num)
                    ->exists();

                if ($existe) {
                    $omitidas++;
                    continue;
                }

                Casa::create([
                    'proyecto_id' => $state['proyecto_id'],
                    'tipo_casa_id' => $rango['tipo_casa_id'],
                    'numero_casa' => (string) $num,
                    'cluster' => $rango['cluster'] ?? null,
                    'estado' => 'no_disponible',
                    'acabados' => false,
                ]);

                $creadas++;
            }
        }

        Notification::make()
            ->title("Se crearon {$creadas} casas")
            ->body($omitidas > 0 ? "{$omitidas} se omitieron por ya existir." : null)
            ->success()
            ->send();

        $this->form->fill();
    }
}