<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reclamos', function (Blueprint $table) {
            if (! Schema::hasColumn('reclamos', 'casa_id')) {
                $table->foreignId('casa_id')->nullable()->after('id')->constrained('casas')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reclamos', function (Blueprint $table) {
            $table->dropForeign(['casa_id']);
            $table->dropColumn('casa_id');
        });
    }
};