<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->foreignId('entrega_id')->nullable()->after('id')->constrained('entregas')->cascadeOnDelete();
        });

        // Migrar el entrega_id desde el reporte_entrega padre, si existía
        if (Schema::hasColumn('reporte_entrega_fotos', 'reporte_entrega_id')) {
            $fotos = DB::table('reporte_entrega_fotos')->get();
            foreach ($fotos as $foto) {
                $reporte = DB::table('reporte_entregas')->find($foto->reporte_entrega_id);
                if ($reporte) {
                    DB::table('reporte_entrega_fotos')->where('id', $foto->id)->update(['entrega_id' => $reporte->entrega_id]);
                }
            }

            Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
                $table->dropForeign(['reporte_entrega_id']);
                $table->dropColumn('reporte_entrega_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('reporte_entrega_fotos', function (Blueprint $table) {
            $table->dropForeign(['entrega_id']);
            $table->dropColumn('entrega_id');
        });
    }
};
