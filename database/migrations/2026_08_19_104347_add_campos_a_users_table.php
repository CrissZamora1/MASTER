<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido')->nullable()->after('name');
            $table->foreignId('rol_id')
                ->nullable()
                ->after('email')
                ->constrained('roles')
                ->nullOnDelete();
            $table->foreignId('proyecto_id')
                ->nullable()
                ->after('rol_id')
                ->constrained('proyectos')
                ->nullOnDelete();
            $table->boolean('activo')->default(true)->after('proyecto_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rol_id');
            $table->dropConstrainedForeignId('proyecto_id');
            $table->dropColumn(['apellido', 'activo']);
        });
    }
};