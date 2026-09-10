<?php

namespace App\Filament\Widgets;

use App\Models\Cita;
use App\Models\Entrega;
use App\Models\ReclamoGarantia;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public static bool $isDiscovered = false;

    public function fetchEvents(array $fetchInfo): array
    {
        $eventos = [];

        $citas = Cita::with(['casa', 'cliente'])
            ->whereBetween('fecha_hora', [$fetchInfo['start'], $fetchInfo['end']])
            ->get();

        foreach ($citas as $cita) {
            $eventos[] = [
                'title' => 'Cita: Casa ' . $cita->casa?->numero_casa . ' - ' . $cita->cliente?->nombre,
                'start' => $cita->fecha_hora,
                'backgroundColor' => $cita->estado === 'reprogramada' ? '#f59e0b' : '#3b82f6',
                'url' => route('filament.admin.resources.citas.view', ['record' => $cita->id]),
            ];
        }

        $entregas = Entrega::with(['casa', 'cliente'])
            ->whereBetween('fecha_hora_entrega', [$fetchInfo['start'], $fetchInfo['end']])
            ->get();

        foreach ($entregas as $entrega) {
            $eventos[] = [
                'title' => 'Entrega: Casa ' . $entrega->casa?->numero_casa . ' (' . $entrega->resultado . ')',
                'start' => $entrega->fecha_hora_entrega,
                'backgroundColor' => match ($entrega->resultado) {
                    'entregada', 'entregada_con_reclamos' => '#22c55e',
                    'no_entregada' => '#ef4444',
                    default => '#6b7280',
                },
                'url' => route('filament.admin.resources.entregas.edit', ['record' => $entrega->id]),
            ];
        }

        $reportes = \App\Models\ReporteReclamo::with(['creadoPor', 'reclamoGarantia.garantia', 'reclamoGarantia.reclamo.casa'])
            ->whereBetween('created_at', [$fetchInfo['start'], $fetchInfo['end']])
            ->get();

        foreach ($reportes as $reporte) {
            $quien = $reporte->esDeSupervisor() ? 'Supervisor' : ($reporte->esDeContratista() ? 'Contratista' : 'Reporte');

            $eventos[] = [
                'title' => $quien . ': ' . $reporte->reclamoGarantia?->garantia?->nombre . ' - Casa ' . $reporte->reclamoGarantia?->reclamo?->casa?->numero_casa,
                'start' => $reporte->created_at,
                'backgroundColor' => $reporte->estado === 'finalizado' ? '#22c55e' : '#a855f7',
                'url' => route('filament.admin.resources.reclamo-garantias.view', ['record' => $reporte->reclamo_garantia_id]),
            ];
        }

        return $eventos;
    }
}
