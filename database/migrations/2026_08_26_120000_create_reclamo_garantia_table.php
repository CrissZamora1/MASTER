<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamo_garantia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamo_id')->constrained('reclamos')->cascadeOnDelete();
            $table->foreignId('garantia_id')->constrained('garantias')->cascadeOnDelete();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('pendiente');
            $table->boolean('validado_manualmente')->default(false);
            $table->timestamps();
        });

        if (Schema::hasColumn('reclamos', 'garantia_id')) {
            $reclamos = DB::table('reclamos')->whereNotNull('garantia_id')->get();

            foreach ($reclamos as $reclamo) {
                DB::table('reclamo_garantia')->insert([
                    'reclamo_id' => $reclamo->id,
                    'garantia_id' => $reclamo->garantia_id,
                    'fecha_inicio' => $reclamo->fecha_inicio,
                    'fecha_fin' => $reclamo->fecha_fin,
                    'estado' => $reclamo->estado ?? 'pendiente',
                    'validado_manualmente' => $reclamo->validado_manualmente ?? false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('reclamos', function (Blueprint $table) {
            if (Schema::hasColumn('reclamos', 'garantia_id')) {
                $table->dropForeign(['garantia_id']);
                $table->dropColumn(['garantia_id', 'fecha_inicio', 'fecha_fin', 'estado', 'validado_manualmente']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('reclamos', function (Blueprint $table) {
            $table->foreignId('garantia_id')->nullable()->constrained('garantias')->nullOnDelete();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('pendiente');
            $table->boolean('validado_manualmente')->default(false);
        });

        Schema::dropIfExists('reclamo_garantia');
    }
};