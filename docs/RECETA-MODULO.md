# Receta para una operación del módulo

Ejemplo conceptual para un módulo con registros ligados a docentes. Cambiar `Registro` por el modelo real de TU módulo y `biblioteca` por el ámbito acordado. No crear modelos genéricos compartidos.

## Lista

```php
public function index(ContextoEscolar $contexto, FiltraRegistros $filtro)
{
    abort_unless($contexto->permite('biblioteca.registros.ver'), 403);
    $query = $filtro->aplicar(Registro::query(), 'biblioteca');
    return Inertia::render('Biblioteca/Registros/Index', [
        'registros' => $query->latest()->paginate(20),
    ]);
}
```

## Policy de acceso por ID

```php
public function view(\Illuminate\Contracts\Auth\Authenticatable $usuario, Registro $registro): bool
{
    return $this->contexto->permite('biblioteca.registros.ver')
        && $this->filtro->aplicar(Registro::query(), 'biblioteca')
            ->whereKey($registro->getKey())->exists();
}
```

La Policy recibe `ContextoEscolar` y `FiltraRegistros` en su constructor. Para mantener el límite del núcleo, tipar el usuario con `Illuminate\Contracts\Auth\Authenticatable`, no importar `App\Core\Models\Usuario`. Registrar explícitamente la Policy en el provider del módulo mediante `Gate::policy`, porque los modelos están en un namespace modular.

El controlador debe invocar `Gate::authorize('view', $registro)` antes de devolverlo. Para update/delete/download se comprueba el permiso específico y el mismo alcance. No basta con validar la lista.

## Escritura

Asignar el periodo desde `ContextoEscolar::periodoId()`, nunca desde un campo confiado del formulario. Verificar que el DNI destino está en el alcance permitido y que existe mediante DirectorioPersonas. Validar los IDs de referencias del propio módulo y los invariantes antes de escribir. Usar transacciones cuando la operación afecte varias tablas propias.

Los módulos de Biblioteca, Oficina y Bienes pueden necesitar alcance por estudiante, oficina o asignación patrimonial: el filtro de docentes NO sustituye sus Policies específicas. Definirlas antes de implementar sus registros reales.

## Contrato nuevo

1. Interfaz en `app/Shared/Contracts`, nombres y tipos claros, sin Eloquent ajeno.
2. Implementación en `Services` del módulo dueño de los datos.
3. Binding en su ServiceProvider, método `register()`.
4. Tests del contrato y aprobación del líder o segundo revisor.
5. Consumidor recibe la interfaz en el constructor, no importa la implementación.

## Migraciones

```powershell
php artisan make:migration create_biblioteca_registros_table --path=app/Modules/Biblioteca/Database/Migrations
php artisan migrate
```

Los providers ya cargan estas carpetas. Crear campos con Schema/Blueprint, prefijo de módulo, FK al DNI de personas, FK al periodo y soft deletes en las tablas principales. Las tablas específicas de libros/equipos apuntarán a inventario_base; no deben crear una segunda tabla central.
