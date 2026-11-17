<?php

namespace Tests\Feature;

use App\Core\Models\Usuario;
use App\Shared\Contracts\ContextoEscolar;
use App\Shared\Contracts\FiltraRegistros;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RegistroPrueba extends Model
{
    protected $table = 'prueba_registros';
}

class ArquitecturaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['demo.password' => 'Solo-para-pruebas-123']);
        $this->seed();
    }

    private function activar(string $dni, string $modulo = 'biblioteca'): int
    {
        $user = Usuario::where('dni', $dni)->firstOrFail();
        $periodo = DB::table('periodos_academicos')->where('en_curso', true)->value('id');
        $perfil = DB::table('perfiles as p')->join('roles as r', 'r.id', '=', 'p.rol_id')
            ->where('p.usuario_id', $user->id)->where('p.periodo_academico_id', $periodo)
            ->where('r.nombre', 'like', "Demo $modulo %")->value('p.id');
        $this->actingAs($user)->withSession(['perfil_id' => $perfil, 'periodo_id' => $periodo]);

        return $perfil;
    }

    public function test_visitante_debe_iniciar_sesion(): void
    {
        $this->get('/biblioteca')->assertRedirect('/login');
    }

    public function test_login_y_cierre_de_sesion(): void
    {
        $this->post('/login', ['dni' => '00000001', 'password' => 'Solo-para-pruebas-123'])->assertRedirect('/contexto');
        $this->assertAuthenticated();
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_rechaza_usuario_desactivado(): void
    {
        Usuario::where('dni', '00000001')->update(['activo' => false]);
        $this->post('/login', ['dni' => '00000001', 'password' => 'Solo-para-pruebas-123'])->assertSessionHasErrors('dni');
        $this->assertGuest();
    }

    public function test_usuario_no_puede_seleccionar_perfil_ajeno(): void
    {
        $this->activar('00000001');
        $otroUsuario = Usuario::where('dni', '00000002')->value('id');
        $otroPerfil = DB::table('perfiles')->where('usuario_id', $otroUsuario)->value('id');
        $this->post('/contexto', ['perfil_id' => $otroPerfil])->assertForbidden();
    }

    public function test_perfil_activo_no_suma_permisos_de_otros_perfiles(): void
    {
        $this->activar('00000001', 'biblioteca');
        $this->get('/biblioteca')->assertOk();
        $this->get('/oficina')->assertForbidden();
    }

    public function test_revocacion_invalida_sesion_existente(): void
    {
        $perfil = $this->activar('00000001');
        DB::table('perfiles')->where('id', $perfil)->update(['activo' => false]);
        $this->get('/biblioteca')->assertForbidden();
    }

    public function test_persona_dada_de_baja_no_accede_con_sesion_existente(): void
    {
        $this->activar('00000001');
        DB::table('personas')->where('dni', '00000001')->update(['deleted_at' => now()]);
        $this->get('/biblioteca')->assertForbidden();
    }

    public function test_cambio_de_periodo_es_atomico_con_su_perfil(): void
    {
        $this->activar('00000001');
        $old = DB::table('periodos_academicos')->whereNull('en_curso')->value('id');
        $profile = DB::table('perfiles')->where('usuario_id', auth()->id())->where('periodo_academico_id', $old)->first();
        $this->post('/contexto', ['perfil_id' => $profile->id])->assertRedirect('/inicio')
            ->assertSessionHas('periodo_id', $old)->assertSessionHas('perfil_id', $profile->id);
    }

    public function test_todos_los_modulos_tienen_rutas_y_permisos(): void
    {
        foreach (config('modulos') as $module) {
            $this->activar('00000001', $module['slug']);
            $this->get('/'.$module['slug'])->assertOk();
        }
    }

    public function test_filtro_aplica_periodo_y_alcance_tambien_por_id(): void
    {
        Schema::create('prueba_registros', function (Blueprint $t) {
            $t->id();
            $t->string('dni_docente', 8);
            $t->unsignedBigInteger('periodo_academico_id');
        });
        $actual = DB::table('periodos_academicos')->where('en_curso', true)->value('id');
        $anterior = DB::table('periodos_academicos')->whereNull('en_curso')->value('id');
        DB::table('prueba_registros')->insert([
            ['id' => 1, 'dni_docente' => '00000003', 'periodo_academico_id' => $actual],
            ['id' => 2, 'dni_docente' => '00000004', 'periodo_academico_id' => $actual],
            ['id' => 3, 'dni_docente' => '00000003', 'periodo_academico_id' => $anterior],
        ]);
        $this->activar('00000001');
        $filtro = app(FiltraRegistros::class);
        $this->assertSame([1, 2], $filtro->aplicar(RegistroPrueba::query(), 'biblioteca')->orderBy('id')->pluck('id')->all());
        $this->activar('00000002');
        $this->assertSame([1], $filtro->aplicar(RegistroPrueba::query(), 'biblioteca')->pluck('id')->all());
        $this->assertFalse($filtro->aplicar(RegistroPrueba::query(), 'biblioteca')->whereKey(2)->exists());
        $this->assertFalse($filtro->aplicar(RegistroPrueba::query(), 'biblioteca')->whereKey(3)->exists());
        $this->activar('00000003');
        $this->assertSame([1], $filtro->aplicar(RegistroPrueba::query(), 'biblioteca')->pluck('id')->all());
        Schema::drop('prueba_registros');
    }

    public function test_coordinacion_vacia_no_se_convierte_en_acceso_total(): void
    {
        $this->activar('00000002');
        $this->assertSame([], app(ContextoEscolar::class)->docentesVisibles('ambito-no-asignado'));
    }

    public function test_seeder_es_repetible(): void
    {
        $count = DB::table('perfiles')->count();
        $this->seed();
        $this->assertSame($count, DB::table('perfiles')->count());
        $this->assertSame(1, DB::table('periodos_academicos')->where('en_curso', true)->count());
    }
}
