<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrega_id')
                ->constrained('entregas')
                ->cascadeOnDelete();

            $table->text('descripcion')->nullable();
            $table->string('foto')->nullable(); // ruta/nombre del archivo

            $table->enum('estado', [
                'pendiente',
                'no_terminado',
                'finalizado',
            ])->default('pendiente');

            // "Encargado": en tu Excel apuntaba a un Área de Trabajo (Textura, Madera...).
            // Por ahora lo dejamos como texto libre; cuando construyamos el módulo
            // de Áreas de Trabajo lo convertimos en foreignId.
            $table->string('encargado')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_entregas');
    }
};