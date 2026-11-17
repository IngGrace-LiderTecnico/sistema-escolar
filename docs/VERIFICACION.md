# Verificación de la base

Fecha: 17 de noviembre de 2026.

## Resultados observados

- Migraciones completas y seeder ejecutados correctamente en PostgreSQL local 18.7, en un clúster temporal independiente.
- 14 pruebas y 78 aserciones aprobadas sobre PostgreSQL: login/logout, usuario desactivado, perfil ajeno, permisos sin suma de perfiles, revocación, baja de persona, cambio atómico de periodo/perfil, siete rutas, alcance por nivel y por ID, coordinador sin asignaciones y seed repetible.
- `npm run build`: correcto, páginas Vue e integración Inertia compiladas.
- Laravel Pint: correcto.
- `composer validate --strict --no-check-publish`: correcto.
- Composer: sin avisos de vulnerabilidades durante la resolución final.
- `npm audit --omit=dev`: cero vulnerabilidades reportadas.

## Límites de verificación

- CI está configurado con PostgreSQL 17 y Node 22; la ejecución local utilizó PostgreSQL 18.7, PHP 8.5.12 y Node 26.13.1. La ejecución remota debe confirmar el entorno estándar del equipo.
- No se realizó revisión visual interactiva en navegador; las pruebas HTTP verifican las respuestas y el build verifica los componentes.
- No se probaron credenciales ni operaciones reales de Drive; su adaptador no está implementado.
- El entorno aislado de Windows reporta falsos negativos en `is_readable`/`is_writable` de PHP aunque permite las operaciones. Las pruebas locales utilizaron un auxiliar temporal que comprueba acceso mediante aperturas reales de archivos. No se modificó vendor ni se incluyó ese auxiliar en el repositorio. Esto no es necesario en CI Linux.

## Aviso pendiente de dependencias de desarrollo

La auditoría completa de npm reporta seis entradas de severidad alta derivadas de `picomatch` (ReDoS, GHSA-fc5q-39xc-vjpf) a través de herramientas de desarrollo como Vite. La versión publicada consultada fue 4.0.7, dentro del rango afectado. No se aplicó el downgrade incompatible sugerido por `npm audit fix --force`.

Seguimiento: actualizar la dependencia y lockfile en cuanto haya una versión corregida compatible y repetir el build. No exponer el servidor de desarrollo públicamente. En producción servir únicamente los assets compilados; no ejecutar Vite como servidor público.

Referencia: https://github.com/advisories/GHSA-fc5q-39xc-vjpf
