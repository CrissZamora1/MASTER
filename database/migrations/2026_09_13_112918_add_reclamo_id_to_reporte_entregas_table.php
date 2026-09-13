<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_entregas', function (Blueprint $table) {
            $table->foreignId('reclamo_id')->nullable()->constrained('reclamos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reporte_entregas', function (Blueprint $table) {
            $table->dropForeign(['reclamo_id']);
            $table->dropColumn('reclamo_id');
        });
    }
};
