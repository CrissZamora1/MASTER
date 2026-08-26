<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('casas', function (Blueprint $table) {
            $table->unique(['proyecto_id', 'numero_casa']);
        });
    }

    public function down(): void
    {
        Schema::table('casas', function (Blueprint $table) {
            $table->dropUnique(['proyecto_id', 'numero_casa']);
        });
    }
};