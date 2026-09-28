<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->foreignId('contratista_id')->nullable()->constrained('contratistas')->nullOnDelete();
            $table->string('ruta_final')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->dropForeign(['contratista_id']);
            $table->dropColumn(['contratista_id', 'ruta_final']);
        });
    }
};
