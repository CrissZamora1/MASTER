<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamo_garantia_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamo_garantia_id')->constrained('reclamo_garantia')->cascadeOnDelete();
            $table->string('ruta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamo_garantia_fotos');
    }
};