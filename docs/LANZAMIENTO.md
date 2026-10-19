# Publicar VendeFacil

Esta guia sirve para cualquier servidor con PHP 8.2+, Composer y MySQL o SQLite. La raiz web recomendada es la carpeta `public/`. Las instrucciones particulares del alojamiento de prueba estan en [deploy-infinityfree.md](deploy-infinityfree.md).

## Antes de publicar

1. Guardar una copia con `php artisan vendefacil:backup` y comprobar que puede recuperarse en un entorno separado.
2. Ejecutar las pruebas y `npm ci && npm run build`. Revisar los resultados de GitHub Actions.
3. Confirmar las paginas de ayuda, condiciones y privacidad, el importe del plan y los datos publicos de soporte.
4. Mantener una sola plantilla: `.env.example`. La instalacion utiliza su `.env` privado. No sobrescribirlo con el de otra maquina.
5. Revisar las credenciales que hayan sido compartidas antes. Cambiar las comprometidas desde el proveedor, sin publicarlas. La rotacion de una `APP_KEY` existente requiere revisar sus datos cifrados y respaldos.

## Instalar o actualizar el servidor

1. Respaldar la version y los datos existentes. Activar mantenimiento durante la actualizacion.
2. Subir el codigo y ejecutar `composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader`. Compilar los assets en el proceso de despliegue o subir `public/build` completo, incluido el manifest.
3. Mantener `storage` y `bootstrap/cache` escribibles por el proceso PHP. Enlazar las imagenes con `php artisan storage:link` donde este disponible.
4. En una instalacion nueva, crear `.env` desde `.env.example`, configurar la base y generar la clave. En una existente, conservar su `APP_KEY`, sus datos y sus archivos.
5. Configurar `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://tu-dominio`, `SESSION_SECURE_COOKIE=true` y `SESSION_ENCRYPT=true`.
6. Configurar el correo real y su direccion remitente. La cuenta Gmail puede usar SMTP; consulta [CONFIGURACION.md](CONFIGURACION.md). Comprobar la entrega real del correo de recuperacion y que el enlace apunta al dominio correcto.
7. Aplicar `php artisan migrate --force`. No importar una base local completa encima de una base con usuarios reales para actualizar su esquema.
8. Ejecutar `php artisan vendefacil:privatize-proofs` para comprobantes antiguos y revisar que no queden copias publicas.
9. Ejecutar `php artisan optimize:clear`, `php artisan config:cache` y `php artisan view:cache`.
10. Crear un respaldo nuevo, ejecutar `php artisan vendefacil:launch-check` y revisar cada pendiente.
11. Activar el programador del servidor y los respaldos diarios como indica [RESPALDOS.md](RESPALDOS.md). Guardar tambien copias en otro almacenamiento privado.
12. Reabrir con `php artisan up` y realizar las comprobaciones funcionales.

## Comprobacion funcional

- Registro, acceso y recuperacion real de contrasena. Las cuentas no requieren verificar correo para entrar.
- Roles: un vendedor no entra al admin ni consulta datos privados de otra cuenta.
- Crear tienda, actualizar ubicacion y fotos, compartir su URL y consultar desde WhatsApp.
- Publicar hasta el limite gratuito y confirmar el bloqueo del siguiente producto.
- Adjuntar un comprobante, revisarlo manualmente y ver el resultado en la cuenta.
- Comprobar que el plan se presenta como pago unico, sin renovacion ni vencimiento programado.
- Revisar favoritos, denuncias, notificaciones y estadisticas.
- Probar movil y escritorio, incluyendo ayuda, privacidad, condiciones y enlaces de soporte.
- Verificar que `/.env`, `/.git/config`, los registros, comprobantes y respaldos no se puedan descargar.
- Abrir `/sitemap.xml` y `/robots.txt` y enviar el sitemap del dominio definitivo a Search Console.

## Archivos que no se publican como contenido web

No subir `.git`, credenciales de desarrollo, respaldos, registros, `node_modules`, pruebas ni ejecutores de diagnostico. `public/hot` es exclusivo del servidor de desarrollo y no debe existir en produccion.

Los comprobantes se conservan en el disco privado y solo se descargan mediante la ruta autenticada. Los respaldos privados tampoco tienen rutas web. No ubicar `storage/app/private` dentro del directorio publico.

## Verificacion local

`php artisan vendefacil:launch-check --local` revisa la instalacion de desarrollo sin exigir un dominio HTTPS de produccion. No cambia configuracion ni envia correos.

Si Windows informa permisos incorrectos en los manifiestos de Laravel aunque pueda escribir archivos, existe `scripts/rebuild-windows-cache.php` como ayuda de desarrollo; no modificar el framework dentro de `vendor`.

La configuracion del servidor definitivo, su cron y la entrega externa de correo deben comprobarse en ese servidor. Que las pruebas locales pasen no sustituye esas comprobaciones.
