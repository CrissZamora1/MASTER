<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_entrega_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_entrega_id')->constrained('reporte_entregas')->cascadeOnDelete();
            $table->string('ruta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_entrega_fotos');
    }
};
