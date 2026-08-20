<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();
            $table->foreignId('tipo_casa_id')
                ->constrained('tipo_casas')
                ->cascadeOnDelete();

            $table->string('numero_casa'); // Ej: "1", "2", "A-12"
            $table->string('cluster')->nullable();
            $table->string('anexo')->nullable();
            $table->boolean('acabados')->default(false);

            $table->enum('estado', [
                'disponible',
                'no_disponible',
                'programada',
                'reprogramada',
                'entregado',
            ])->default('no_disponible');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casas');
    }
};