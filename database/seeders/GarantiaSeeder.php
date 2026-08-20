<?php

namespace Database\Seeders;

use App\Models\Garantia;
use Illuminate\Database\Seeder;

class GarantiaSeeder extends Seeder
{
    public function run(): void
    {
        $garantias = [
            ['nombre' => 'Cierres de ventanas', 'meses_duracion' => 3],
            ['nombre' => 'Chapas de puertas', 'meses_duracion' => 3],
            ['nombre' => 'Mezcladoras', 'meses_duracion' => 3],
            ['nombre' => 'Llaves de ducha', 'meses_duracion' => 3],
            ['nombre' => 'Cabezas de ducha', 'meses_duracion' => 3],
            ['nombre' => 'Chorros (pila, patio y carport)', 'meses_duracion' => 3],
            ['nombre' => 'Mangueras de mezcladoras', 'meses_duracion' => 3],
            ['nombre' => 'Contrallaves', 'meses_duracion' => 3],
            ['nombre' => 'Pila', 'meses_duracion' => 3],
            ['nombre' => 'Funcionamiento de losa sanitaria', 'meses_duracion' => 3],
            ['nombre' => 'Tuberías de agua potable (Fría y caliente)', 'meses_duracion' => 6],
            ['nombre' => 'Drenajes (Pluvial y Sanitario)', 'meses_duracion' => 6],
            ['nombre' => 'Ductos secos y circuitos eléctricos', 'meses_duracion' => 6],
            ['nombre' => 'Interruptores', 'meses_duracion' => 6],
            ['nombre' => 'Tomacorrientes (110v y 220v)', 'meses_duracion' => 6],
            ['nombre' => 'Fisuras que transmitan filtraciones al interior', 'meses_duracion' => 18],
            ['nombre' => 'Filtraciones de agua en losas', 'meses_duracion' => 18],
            ['nombre' => 'Filtraciones de agua en muros', 'meses_duracion' => 18],
            ['nombre' => 'Ventanería que transmita filtraciones al interior de la casa', 'meses_duracion' => 18],
        ];

        foreach ($garantias as $g) {
            Garantia::updateOrCreate(['nombre' => $g['nombre']], $g);
        }
    }
}