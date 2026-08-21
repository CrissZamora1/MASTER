<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['codigo' => 'SUPER', 'nombre' => 'Superintendente'],
            ['codigo' => 'ADMIN', 'nombre' => 'Ingeniero'],
            ['codigo' => 'SUP', 'nombre' => 'Supervisor'],
            ['codigo' => 'CONT', 'nombre' => 'Contratista'],
        ];

        foreach ($roles as $rol) {
            Rol::updateOrCreate(['codigo' => $rol['codigo']], $rol);
        }
    }
}