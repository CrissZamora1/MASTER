<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->text('descripcion')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente | finalizado
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_at')->nullable();
            $table->foreignId('reclamo_id')->nullable()->constrained('reclamos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->dropForeign(['aprobado_por']);
            $table->dropForeign(['reclamo_id']);
            $table->dropColumn(['descripcion', 'estado', 'aprobado_por', 'aprobado_at', 'reclamo_id']);
        });
    }
};
