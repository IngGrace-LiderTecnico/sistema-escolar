<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Los datos de demostración solo se cargan en local/testing.');
        }
        $password = config('demo.password');
        if (! $password) {
            throw new \RuntimeException('Configura DEMO_PASSWORD en .env antes de ejecutar el seeder.');
        }
        DB::transaction(function () use ($password) {
            $now = now();
            $year = (int) date('Y');
            foreach ([$year - 1, $year] as $y) {
                DB::table('periodos_academicos')->updateOrInsert(['nombre' => (string) $y], ['inicio' => "$y-01-01", 'fin' => "$y-12-31", 'en_curso' => $y === $year ? true : null, 'created_at' => $now, 'updated_at' => $now]);
            }
            $periods = DB::table('periodos_academicos')->pluck('id');
            foreach (['00000001', '00000002', '00000003', '00000004'] as $i => $dni) {
                DB::table('personas')->updateOrInsert(['dni' => $dni], ['nombres' => 'Persona Demo '.($i + 1), 'apellidos' => 'Datos Ficticios', 'tipo' => 'docente', 'created_at' => $now, 'updated_at' => $now]);
                DB::table('usuarios')->updateOrInsert(['dni' => $dni], ['password' => Hash::make($password), 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
            }
            DB::table('personas')->updateOrInsert(['dni' => '00000005'], ['nombres' => 'Estudiante Demo', 'apellidos' => 'Datos Ficticios', 'tipo' => 'estudiante', 'created_at' => $now, 'updated_at' => $now]);
            foreach (config('modulos') as $module) {
                $slug = $module['slug'];
                $permission = DB::table('permisos')->updateOrInsert(['nombre' => "$slug.ver"], ['created_at' => $now, 'updated_at' => $now]);
                $permissionId = DB::table('permisos')->where('nombre', "$slug.ver")->value('id');
                foreach (['maximo', 'medio', 'basico'] as $i => $level) {
                    $roleName = "Demo $slug $level";
                    DB::table('roles')->updateOrInsert(['nombre' => $roleName], ['nivel' => $level, 'created_at' => $now, 'updated_at' => $now]);
                    $roleId = DB::table('roles')->where('nombre', $roleName)->value('id');
                    DB::table('permiso_rol')->updateOrInsert(['rol_id' => $roleId, 'permiso_id' => $permissionId]);
                    $dni = ['00000001', '00000002', '00000003'][$i];
                    foreach ($periods as $period) {
                        $userId = DB::table('usuarios')->where('dni', $dni)->value('id');
                        DB::table('perfiles')->updateOrInsert(['usuario_id' => $userId, 'rol_id' => $roleId, 'periodo_academico_id' => $period], ['activo' => true, 'created_at' => $now, 'updated_at' => $now]);
                    }
                }
                foreach ($periods as $period) {
                    DB::table('coordinador_docente')->updateOrInsert(['dni_coordinador' => '00000002', 'dni_docente' => '00000003', 'periodo_academico_id' => $period, 'ambito' => $slug], ['created_at' => $now, 'updated_at' => $now]);
                }
            }
        });
    }
}
