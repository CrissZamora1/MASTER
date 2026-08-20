<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casa_id')
                ->constrained('casas')
                ->cascadeOnDelete();
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->cascadeOnDelete();

            $table->string('tipo_cita')->nullable(); // Ej: "Entrega", "Revisión", etc.
            $table->dateTime('fecha_hora');

            $table->enum('estado', [
                'programada',
                'reprogramada',
            ])->default('programada');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};