<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_reclamos', function (Blueprint $table) {
            $table->foreignId('reclamo_garantia_id')->nullable()->after('reclamo_id')->constrained('reclamo_garantia')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reporte_reclamos', function (Blueprint $table) {
            $table->dropForeign(['reclamo_garantia_id']);
            $table->dropColumn('reclamo_garantia_id');
        });
    }
};