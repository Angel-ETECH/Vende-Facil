# Publicar esta version

## Preparacion local

1. Ejecutar `composer install`, `php artisan migrate` y `php artisan vendefacil:privatize-proofs`.
2. Ejecutar `php artisan test` y `npm run build`.
3. No subir `.git`, `.env` de desarrollo, `node_modules`, pruebas, backups ni registros.
4. Nunca subir `public/hot`: apunta al servidor de desarrollo.

## Actualizacion del hosting

1. Respaldar base de datos y archivos antes de reemplazar la version.
2. Subir el codigo, las dependencias de produccion y `public/build` completo, incluido su manifest.
3. Mantener los archivos privados fuera de la carpeta publica. La raiz del dominio debe apuntar a `public`; si se usa la estructura alternativa de InfinityFree, subir tambien ambos `.htaccess` y comprobar sus bloqueos.
4. Aplicar la nueva migracion `2026_10_12_000004_add_launch_features`. Si el hosting no permite Artisan, migrar una copia de la base localmente y preparar/importar el cambio de esquema revisado. No crear ejecutores publicos de comandos o SQL.
5. Mover los comprobantes antiguos de `storage/app/public/payment-proofs` a `storage/app/private/payment-proofs` conservando sus nombres. Con Artisan: `php artisan vendefacil:privatize-proofs`. Verificar la copia antes de retirar el original. Si existe una copia fisica en `public/storage`, retirar tambien sus comprobantes.
6. En el `.env` del hosting usar `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://tu-dominio`, `SESSION_SECURE_COOKIE=true` y `SESSION_ENCRYPT=true`.
7. Configurar un servicio SMTP real: `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` y `MAIL_FROM_NAME`. Usar los valores del proveedor. `MAIL_MAILER=log` sirve para desarrollo, pero no envia correos a los usuarios.
8. Cambiar la contrasena expuesta de la base en el panel y actualizar `DB_PASSWORD`. No poner credenciales reales en archivos `.example`.
9. Planificar el cambio de la `APP_KEY` expuesta con respaldo. Una clave nueva invalida sesiones y puede impedir leer datos cifrados con la anterior. No regenerarla sin revisar los datos existentes.
10. Ejecutar `php artisan optimize:clear`, `php artisan config:cache`, `php artisan view:cache` y `php artisan vendefacil:launch-check` donde exista consola. No mostrar errores detallados al publico.

## Comprobacion final

- Registrar una cuenta e iniciar sesion; confirmar que puede entrar sin verificar correo y que solo los administradores acceden al admin.
- Recuperar una contrasena desde un correo real.
- Publicar 10 productos y comprobar que el producto 11 se bloquea.
- Enviar comprobante; comprobar que otro vendedor no puede descargarlo.
- Aprobar el pago y ver activado el plan; rechazar otro con motivo y comprobar la notificacion.
- Guardar/quitar favoritos; denunciar un producto; revisar/ocultar la publicacion desde el admin.
- Ver estadisticas y confirmar que son consultas, no ventas confirmadas.
- Probar en movil y escritorio, con fotos reales, menus, mapas y galerias.
- Comprobar que `/.env`, `/.git/config`, `/storage/logs/laravel.log`, `/storage/app/private/...` y `/storage/payment-proofs/...` no entregan archivos.

## Correos y cuentas existentes

La verificacion de correo no es obligatoria para ingresar o administrar una tienda. Las cuentas existentes pueden entrar sin modificar `email_verified_at`. En produccion se necesita SMTP funcionando para enviar los correos de recuperacion de contrasena.

Las notificaciones de pagos y denuncias se guardan dentro de la cuenta. Los correos de recuperacion usan el sistema de correo de Laravel; no se envia verificacion automatica al registrar una cuenta.

El disco privado no sirve archivos mediante enlaces temporales publicos. Los comprobantes pasan por una ruta autenticada que comprueba al propietario o administrador y descarga el archivo como adjunto.

Las imagenes nuevas se convierten a WebP con un maximo de 1600 pixeles por lado cuando GD/WebP esta disponible. Las anteriores se conservan. Sin GD se guarda el archivo validado original.

En algunos entornos restringidos de Windows, PHP informa que la cache no es escribible aunque puede guardar archivos. Solo para desarrollo, `php scripts/rebuild-windows-cache.php` reconstruye los manifiestos usando los proveedores instalados. No modificar archivos de Laravel en `vendor` para resolverlo.

La auditoria de npm de dependencias de produccion no reporto vulnerabilidades durante esta mejora. La cadena de herramientas de Tailwind 3 conserva avisos de desarrollo; se debe planificar su actualizacion y validar los estilos antes de cambiar a Tailwind 4. No usar `npm audit fix --force` sin revisar la migracion.
