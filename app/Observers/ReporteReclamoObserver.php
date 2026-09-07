<?php

namespace App\Observers;

use App\Models\ReporteReclamo;

class ReporteReclamoObserver
{
    public function saved(ReporteReclamo $reporte): void
    {
        $ticket = $reporte->reclamoGarantia;

        if (! $ticket) {
            return;
        }

        $reportes = $ticket->reportes()->with('creadoPor')->get();

        $contratistaFinalizado = $reportes->first(fn ($r) => $r->esDeContratista() && $r->estado === 'finalizado');
        $supervisorFinalizado = $reportes->first(fn ($r) => $r->esDeSupervisor() && $r->estado === 'finalizado');

        if ($contratistaFinalizado && $supervisorFinalizado) {
            if ($ticket->estado_reparacion !== 'finalizada') {
                $ticket->update(['estado_reparacion' => 'finalizada']);
            }
            return;
        }

        // Si el Contratista ya reportó algo (aunque no esté finalizado), ya "empezó"
        $hayReporteContratista = $reportes->first(fn ($r) => $r->esDeContratista());

        if ($hayReporteContratista && $ticket->estado_reparacion === 'pendiente') {
            $ticket->update(['estado_reparacion' => 'en_proceso']);
        }
    }
}