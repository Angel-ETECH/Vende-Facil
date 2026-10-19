# Alojamiento de prueba en InfinityFree

Esta guia es opcional y especifica del alojamiento donde se probo VendeFacil. El proyecto se publica en cualquier servidor Laravel compatible siguiendo [LANZAMIENTO.md](LANZAMIENTO.md).

## Preparar una copia para subir

1. Crear un respaldo privado antes de actualizar.
2. Trabajar sobre una copia de despliegue para no quitar las dependencias de desarrollo de tu instalacion habitual.
3. En esa copia ejecutar `composer install --no-dev --prefer-dist --no-interaction`, limpiar las caches locales y compilar con `npm ci && npm run build`.
4. No subir `.git`, `node_modules`, registros, respaldos, pruebas, `public/hot` ni el `.env` de tu PC.
5. Mantener el `.env` existente del servidor. En una instalacion nueva, usar la unica plantilla `.env.example`, completar sus datos y generar una clave nueva antes de subirla.

## Base de datos y actualizaciones

La cuenta gratuita de este alojamiento se uso sin consola SSH, Composer ni Artisan. Prepara las dependencias y el esquema fuera del servidor.

En una primera instalacion se puede importar la base preparada. Para una actualizacion con usuarios reales, respalda la base alojada y prepara los cambios de esquema sobre una copia. No reemplaces la base del servidor con una exportacion de desarrollo, porque perderias sus datos nuevos. No crees rutas publicas para ejecutar SQL o comandos.

## Estructura alternativa

La raiz del dominio debe apuntar a `public/` cuando sea posible. En la estructura de prueba `htdocs/` se usaron los dos `.htaccess` del repositorio para redirigir el trafico y bloquear carpetas internas. Ambos son necesarios en esa estructura.

Las imagenes de productos y logos se guardan en `storage/app/public`. Si no hay enlaces simbolicos, la regla de `.htaccess` para `/storage/...` permite servir esas imagenes. Nunca copies comprobantes o respaldos a esa carpeta: son privados y usan rutas autenticadas, o consola en el caso de los respaldos.

## Configuracion del servidor

Usa `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://tu-dominio`, cookies seguras y las credenciales de la base alojada. Configura Gmail u otro proveedor SMTP siguiendo [CONFIGURACION.md](CONFIGURACION.md).

Conserva la `APP_KEY` del servidor existente. Para diagnosticar un error, revisa los registros privados; no actives detalles de depuracion publicos. No publiques credenciales en capturas.

## Comprobaciones

Prueba registro, acceso, recuperacion real de contrasena, tienda, fotos, ubicacion, limite gratuito y revision de pagos. Comprueba que `/.env`, `/.git/config`, registros, comprobantes y respaldos no se descarguen.

Si el alojamiento no admite ejecutar tareas programadas, no habra respaldos automaticos solo por subir el codigo. Usa una estrategia compatible con el proveedor o elige un servidor que permita activar el programador descrito en [RESPALDOS.md](RESPALDOS.md).
