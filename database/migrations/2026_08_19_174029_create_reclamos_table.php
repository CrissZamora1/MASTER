<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casa_id')
                ->constrained('casas')
                ->cascadeOnDelete();
            $table->foreignId('garantia_id')
                ->constrained('garantias')
                ->cascadeOnDelete();

            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->enum('estado', [
                'garantia_aceptada',
                'fuera_de_garantia',
                'pendiente',
            ])->default('pendiente');

            $table->text('descripcion')->nullable();
            $table->string('ticket')->nullable();
            $table->date('fecha_reporte')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamos');
    }
};