<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_casas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();
            $table->string('nombre'); // Ej: "2N", "3N", "3NB"
            $table->decimal('metros', 8, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_casas');
    }
};