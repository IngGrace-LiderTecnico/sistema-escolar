# Sistema Escolar

Base de desarrollo para dos equipos y siete módulos. Laravel, PostgreSQL, Vue, Inertia y Tailwind; un repositorio, una aplicación y una base de datos.

## Estado de esta entrega

Implementado: migraciones y seeders del núcleo, autenticación por DNI, perfil activo por periodo, permisos desde la base, filtro reutilizable por nivel, siete módulos con rutas y pantallas iniciales, layout y componentes comunes, contratos, pruebas y workflow de CI.

Las pantallas de los módulos son espacios de arranque, no funcionalidades terminadas. Drive tiene un contrato y una implementación que falla explícitamente hasta configurar la integración institucional. No se ha implementado el cierre anual destructivo. No hay administración de usuarios ni recuperación de contraseñas todavía.

## Versiones del equipo

| Herramienta | Línea acordada para esta base |
|---|---|
| PHP | 8.5.x |
| Composer | 2.x |
| Laravel | 13.x, versión exacta en composer.lock |
| Node | 22.x, mínimo 22.12 |
| PostgreSQL | 17.x en Docker y CI |
| Vue / Inertia / Tailwind | 3.x / 3.x / 4.x; versiones exactas en package-lock.json |

Se versionan ambos lockfiles. Usar `composer install` y `npm ci`; no actualizar dependencias por módulo. Las actualizaciones de seguridad se coordinan en un PR común.

## Arranque local

Requiere PHP con pdo_pgsql, mbstring, intl, curl, openssl, fileinfo, dom y xml; Composer, Node y PostgreSQL (instalación propia o Docker).

```powershell
git clone https://github.com/IngGrace-LiderTecnico/sistema-escolar.git
cd sistema-escolar
Copy-Item .env.example .env
composer install
npm ci
php artisan key:generate
```

Editar `.env`: `DB_USERNAME`, `DB_PASSWORD` y `DEMO_PASSWORD` con valores locales. `DB_DATABASE=sistema_escolar`. Crear esa base en tu PostgreSQL o iniciar el contenedor:

```powershell
docker compose up -d postgres
php artisan migrate --seed
npm run build
php artisan serve
```

Abrir http://127.0.0.1:8000. Para desarrollo con recarga, ejecutar `npm run dev` en otra terminal. Si se utiliza PostgreSQL instalado, omitir Docker. Las tablas siempre se crean mediante las migraciones.

Datos ficticios: DNI `00000001` tiene perfiles máximos de demostración; `00000002`, medios; `00000003`, básicos; `00000004` es un docente sin perfil; `00000005`, un estudiante sin usuario. La contraseña es la que configuraste en `DEMO_PASSWORD`. Los permisos de cada perfil están limitados a un módulo. Se incluyen el año de ejecución y el anterior.

Los roles de demo NO son el organigrama del colegio. El líder debe definir asignaciones reales antes de producción. El seeder solo funciona en `local` y `testing`, y nunca debe cargar datos de demo en producción.

## Dónde trabaja cada equipo

| Equipo | Módulo | Backend | Páginas Vue | Prefijo de tablas |
|---|---|---|---|---|
| A | Oficina | app/Modules/Oficina | resources/js/Pages/Oficina | oficina_ |
| A | Biblioteca | app/Modules/Biblioteca | resources/js/Pages/Biblioteca | biblioteca_ |
| A | Bienes Patrimoniales | app/Modules/Bienes | resources/js/Pages/Bienes | bienes_ |
| B | Subdirección de Humanidades | app/Modules/Humanidades | resources/js/Pages/Humanidades | humanidades_ |
| B | Subdirección de Taller | app/Modules/Taller | resources/js/Pages/Taller | taller_ |
| B | Coordinadores Pedagógicos | app/Modules/Coordinacion | resources/js/Pages/Coordinacion | coordinacion_ |
| B | Jefes de Taller | app/Modules/Jefatura | resources/js/Pages/Jefatura | jefatura_ |

Cada módulo tiene Controller, Requests, Models, Policies, Services, Routes y Database/Migrations. Su provider ya carga sus rutas y migraciones. No hace falta editar `routes/web.php` ni `bootstrap/providers.php` para agregar funcionalidad dentro de un módulo existente.

## Validación

```powershell
php artisan test
npm run build
vendor/bin/pint --test
```

Las pruebas usan SQLite en memoria por defecto para facilitar el desarrollo. CI fuerza PostgreSQL en una base exclusiva de pruebas y comprueba `migrate:fresh --seed`, tests, build y formato. Nunca apuntar las pruebas a una base con datos que se necesiten conservar: RefreshDatabase y migrate:fresh son destructivos.

## Lecturas para empezar

- [Arquitectura y decisiones](docs/ARQUITECTURA.md)
- [Trabajo inicial y criterios de integración](docs/ARRANQUE-EQUIPOS.md)
- [Cómo implementar un módulo](docs/RECETA-MODULO.md)
- [Resultado de la verificación local](docs/VERIFICACION.md)

## GitHub

Crear ramas por tarea, por ejemplo `biblioteca/prestamos`. Antes de programar: `git fetch origin` y `git merge origin/main` desde tu rama. Abrir PR hacia main, con revisión del líder o segundo revisor.

El workflow de CI está incluido, pero las protecciones del repositorio son configuración de GitHub: exigir revisión y el check `test`, bloquear force pushes y pushes directos. Falta asignar líderes, responsables y CODEOWNERS con usuarios reales.
