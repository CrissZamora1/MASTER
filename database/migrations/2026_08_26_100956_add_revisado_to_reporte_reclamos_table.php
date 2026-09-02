<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_reclamos', function (Blueprint $table) {
            $table->boolean('revisado')->default(false);
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('revisado_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reporte_reclamos', function (Blueprint $table) {
            $table->dropForeign(['revisado_por']);
            $table->dropColumn(['revisado', 'revisado_por', 'revisado_at']);
        });
    }
};