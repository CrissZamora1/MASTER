<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')
                ->nullable()
                ->constrained('citas')
                ->nullOnDelete();
            $table->foreignId('casa_id')
                ->constrained('casas')
                ->cascadeOnDelete();
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->cascadeOnDelete();

            $table->dateTime('fecha_hora_entrega');

            $table->enum('resultado', [
                'entregada',
                'entregada_con_reclamos',
                'no_entregada',
            ])->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};