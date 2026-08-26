<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reclamos', function (Blueprint $table) {
            if (! Schema::hasColumn('reclamos', 'garantia_id')) {
                $table->foreignId('garantia_id')->nullable()->after('casa_id')->constrained('garantias')->nullOnDelete();
            }
            if (! Schema::hasColumn('reclamos', 'fecha_inicio')) {
                $table->date('fecha_inicio')->nullable()->after('garantia_id');
            }
            if (! Schema::hasColumn('reclamos', 'fecha_fin')) {
                $table->date('fecha_fin')->nullable()->after('fecha_inicio');
            }
            if (! Schema::hasColumn('reclamos', 'estado')) {
                $table->string('estado')->default('pendiente')->after('fecha_fin');
            }
            if (! Schema::hasColumn('reclamos', 'validado_manualmente')) {
                $table->boolean('validado_manualmente')->default(false)->after('estado');
            }
            if (! Schema::hasColumn('reclamos', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('validado_manualmente');
            }
            if (! Schema::hasColumn('reclamos', 'ticket')) {
                $table->string('ticket')->nullable()->after('descripcion');
            }
            if (! Schema::hasColumn('reclamos', 'fecha_reporte')) {
                $table->date('fecha_reporte')->nullable()->after('ticket');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reclamos', function (Blueprint $table) {
            $table->dropColumn(['garantia_id', 'fecha_inicio', 'fecha_fin', 'estado', 'validado_manualmente', 'descripcion', 'ticket', 'fecha_reporte']);
        });
    }
};