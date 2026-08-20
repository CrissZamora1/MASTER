<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Cierres de ventanas", "Pila"
            $table->unsignedSmallInteger('meses_duracion');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};