<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reclamo_garantia', function (Blueprint $table) {
            $table->foreignId('contratista_id')->nullable()->constrained('contratistas')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('estado_reparacion')->default('pendiente'); // pendiente | en_proceso | finalizada
        });
    }

    public function down(): void
    {
        Schema::table('reclamo_garantia', function (Blueprint $table) {
            $table->dropForeign(['contratista_id']);
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn(['contratista_id', 'supervisor_id', 'estado_reparacion']);
        });
    }
};