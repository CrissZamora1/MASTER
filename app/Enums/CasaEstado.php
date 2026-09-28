<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CasaEstado: string implements HasLabel, HasColor
{
    case Disponible = 'disponible';
    case NoDisponible = 'no_disponible';
    case Programada = 'programada';
    case Reprogramada = 'reprogramada';
    case Entregado = 'entregado';
    case EntregadoConReclamos = 'entregado_con_reclamos';
    case NoAsistio = 'no_asistio';

    public function getLabel(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::NoDisponible => 'No disponible',
            self::Programada => 'Programada',
            self::Reprogramada => 'Reprogramada',
            self::Entregado => 'Entregado',
            self::EntregadoConReclamos => 'Entregado con reclamos',
            self::NoAsistio => 'No asistió',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Disponible => 'success',
            self::NoDisponible, self::NoAsistio => 'danger',
            self::Programada, self::Reprogramada, self::EntregadoConReclamos => 'warning',
            self::Entregado => 'primary',
        };
    }
}