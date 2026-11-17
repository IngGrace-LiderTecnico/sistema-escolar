# Arranque de los equipos

## Puerta de entrada

Cada integrante debe poder instalar dependencias, migrar y cargar demo, iniciar sesión, escoger un perfil, abrir su módulo y ejecutar tests/build. Solo entonces empieza una funcionalidad de negocio.

## Primera entrega por responsable

| Responsable | Entrega pequeña y comprobable | Dependencia |
|---|---|---|
| Líder | Revisar núcleo y matriz de permisos; habilitar protección de main | Esta base |
| Segundo revisor | Revisar contratos y prueba común de autorización | Núcleo |
| Oficina | Primer listado y formulario de su proceso acordado | DirectorioPersonas, layout |
| Biblioteca | Catálogo y contrato de préstamos pendientes | Acordar inventario con Bienes |
| Bienes | Detalle de equipos y asignaciones por periodo | Mismo contrato de inventario |
| Humanidades | Primer flujo de consulta/autorización acordado | Matriz de permisos |
| Taller | Primer flujo de consulta/autorización acordado | Matriz de permisos |
| Coordinación | Primer registro propio y listado con filtro medio | Coordinaciones por periodo |
| Jefatura | Primer flujo propio, sin duplicar datos de Coordinación | Definir dueño de documentos |

Los nombres de las funcionalidades son orientativos; la guía no contiene requisitos de negocio completos. Abrir un issue con actor, acción, campos, permiso, periodo y criterio de aceptación antes de diseñar tablas.

## Orden de integración

1. Integrar la base común y validar instalación en las seis máquinas.
2. Resolver inventario, contrato de pendientes y dueño de documentos compartidos.
3. Cada módulo entrega una operación vertical pequeña: migración, servicio, Policy, controlador, página y test.
4. Integrar contratos de lectura entre módulos; usar dobles en tests hasta que exista la implementación real.
5. Conectar Drive y probar errores/reintentos antes de habilitar documentos reales.
6. Dejar cierre anual y limpieza masiva para un hito posterior con política aprobada.

## Criterio de terminado de una funcionalidad

- Permiso en base; nunca comparar por nombre de cargo en código.
- Periodo y alcance comprobados en lista y acceso por ID, incluidas descarga y exportación.
- Migración reversible y datos ficticios; sin modelos compartidos entre módulos.
- Test de éxito y test de acceso denegado con otro usuario o periodo.
- UI usa Shared, errores legibles y estados sin datos.
- PR sin secretos, con tests/build verdes y revisión de un responsable.

## Coordinación de cambios

No editar las migraciones publicadas del núcleo. Agregar una nueva migración en un PR del núcleo. Los módulos reservan nombres de tabla con su prefijo. Los contratos publicados se cambian con todos sus consumidores en el mismo PR o mediante una versión nueva compatible.

El test de límites detecta imports directos entre namespaces. No pretende detectar consultas SQL dinámicas o llamadas HTTP construidas en ejecución: la revisión del PR sigue siendo obligatoria.
