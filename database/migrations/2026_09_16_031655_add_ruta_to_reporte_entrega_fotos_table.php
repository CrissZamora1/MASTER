<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            if (! Schema::hasColumn('reporte_entrega_fotos', 'ruta')) {
                $table->string('ruta')->after('entrega_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->dropColumn('ruta');
        });
    }
};
