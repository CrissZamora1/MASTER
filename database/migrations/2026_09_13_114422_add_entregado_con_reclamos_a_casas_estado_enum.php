<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE casas MODIFY estado ENUM('disponible','no_disponible','programada','reprogramada','entregado','entregado_con_reclamos','no_asistio') NOT NULL DEFAULT 'no_disponible'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE casas MODIFY estado ENUM('disponible','no_disponible','programada','reprogramada','entregado','no_asistio') NOT NULL DEFAULT 'no_disponible'");
    }
};
