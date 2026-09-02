<?php
namespace App\Observers;

use App\Models\ReporteReclamo;
use App\Models\Reclamo;

class ReporteReclamoObserver
{
    public function saved(ReporteReclamo $reporteReclamo): void
    {
        // Obtenemos el reclamo padre
        $reclamo = $reporteReclamo->reclamo;

        if (!$reclamo) return;

        // Buscamos todos los reportes de este reclamo
        $reportes = $reclamo->reportes;

        // Lógica: ¿Hay al menos uno de Supervisor FINALIZADO?
        $tieneSupFinalizado = $reportes->contains(fn ($r) => 
            $r->esDeSupervisor() && $r->estado === 'finalizado'
        );

        // Lógica: ¿Hay al menos uno de Contratista FINALIZADO?
        $tieneContFinalizado = $reportes->contains(fn ($r) => 
            $r->esDeContratista() && $r->estado === 'finalizado'
        );

        // Si AMBOS terminaron su parte, cerramos el Reclamo automáticamente
        if ($tieneSupFinalizado && $tieneContFinalizado) {
            $reclamo->update([
                'estado' => 'finalizado' // Asegúrate que este estado exista en tu DB
            ]);
        }
    }
}