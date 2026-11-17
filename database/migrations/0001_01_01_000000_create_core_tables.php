<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $t) {
            $t->string('dni', 8)->primary();
            $t->string('nombres');
            $t->string('apellidos');
            $t->string('tipo');
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('usuarios', function (Blueprint $t) {
            $t->id();
            $t->string('dni', 8)->unique();
            $t->foreign('dni')->references('dni')->on('personas')->restrictOnDelete();
            $t->string('password');
            $t->boolean('activo')->default(true);
            $t->rememberToken();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('periodos_academicos', function (Blueprint $t) {
            $t->id();
            $t->string('nombre')->unique();
            $t->date('inicio');
            $t->date('fin');
            // TRUE or NULL: unique allows multiple historical periods and at most one active.
            $t->boolean('en_curso')->nullable()->unique();
            $t->timestamps();
        });
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('nombre')->unique();
            $t->enum('nivel', ['maximo', 'medio', 'basico']);
            $t->timestamps();
        });
        Schema::create('permisos', function (Blueprint $t) {
            $t->id();
            $t->string('nombre')->unique();
            $t->timestamps();
        });
        Schema::create('permiso_rol', function (Blueprint $t) {
            $t->foreignId('rol_id')->constrained('roles')->restrictOnDelete();
            $t->foreignId('permiso_id')->constrained('permisos')->restrictOnDelete();
            $t->primary(['rol_id', 'permiso_id']);
        });
        Schema::create('perfiles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('usuario_id')->constrained('usuarios')->restrictOnDelete();
            $t->foreignId('rol_id')->constrained('roles')->restrictOnDelete();
            $t->foreignId('periodo_academico_id')->constrained('periodos_academicos')->restrictOnDelete();
            $t->boolean('activo')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->unique(['usuario_id', 'rol_id', 'periodo_academico_id']);
        });
        Schema::create('coordinador_docente', function (Blueprint $t) {
            $t->id();
            $t->string('dni_coordinador', 8);
            $t->string('dni_docente', 8);
            $t->foreign('dni_coordinador')->references('dni')->on('personas')->restrictOnDelete();
            $t->foreign('dni_docente')->references('dni')->on('personas')->restrictOnDelete();
            $t->foreignId('periodo_academico_id')->constrained('periodos_academicos')->restrictOnDelete();
            $t->string('ambito', 40);
            $t->timestamps();
            $t->softDeletes();
            $t->unique(['dni_coordinador', 'dni_docente', 'periodo_academico_id', 'ambito'], 'coordinacion_unica');
        });
        Schema::create('inventario_base', function (Blueprint $t) {
            $t->id();
            $t->string('codigo')->unique();
            $t->string('descripcion');
            $t->string('estado');
            $t->enum('propietario', ['biblioteca', 'bienes']);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('sessions', function (Blueprint $t) {
            $t->string('id')->primary();
            $t->foreignId('user_id')->nullable()->index();
            $t->string('ip_address', 45)->nullable();
            $t->text('user_agent')->nullable();
            $t->longText('payload');
            $t->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        foreach (['sessions', 'inventario_base', 'coordinador_docente', 'perfiles', 'permiso_rol', 'permisos', 'roles', 'periodos_academicos', 'usuarios', 'personas'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
