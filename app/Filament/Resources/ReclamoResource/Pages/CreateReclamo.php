<?php

namespace App\Filament\Resources\ReclamoResource\Pages;

use App\Filament\Resources\ReclamoResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ReporteReclamo;

class CreateReclamo extends CreateRecord
{
    protected static string $resource = ReclamoResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();

        // Creamos el primer reporte automáticamente vinculado al reclamo recién creado
        ReporteReclamo::create([
            'reclamo_id' => $this->record->id,
            'creado_por_user_id' => auth()->id(),
            'descripcion' => $data['descripcion'],
            'estado' => 'pendiente',
            'revisado' => false,
            // Aquí podrías guardar también el contratista_id si tu tabla de reportes lo tiene
        ]);
        
        // Si necesitas guardar el contratista en el Reclamo, asegúrate que la columna exista
        $this->record->update([
            'contratista_id' => $data['contratista_id']
        ]);
    }
}