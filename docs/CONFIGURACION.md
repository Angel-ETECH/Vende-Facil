# Una sola configuracion por instalacion

El proyecto se puede instalar en cualquier servidor compatible con Laravel. InfinityFree fue una prueba de alojamiento, no una dependencia de la aplicacion.

## Archivos de entorno

- `.env`: valores reales de esta instalacion. No se versiona ni se comparte.
- `.env.example`: unica plantilla del repositorio, sin claves reales. Sirve para instalaciones nuevas.

No copies la plantilla sobre un `.env` que ya funciona. El antiguo respaldo de InfinityFree se conserva fuera del proyecto en la carpeta privada del usuario que hizo la limpieza.

Los cambios de configuracion se aplican con `php artisan config:clear`. Si el servidor utiliza cache de configuracion, vuelve a ejecutar `php artisan config:cache` despues de revisar los valores.

## Correo con Gmail

Gmail usa `MAIL_MAILER=smtp`, `MAIL_SCHEME=smtp`, `MAIL_HOST=smtp.gmail.com` y `MAIL_PORT=587`. `MAIL_USERNAME` y `MAIL_FROM_ADDRESS` deben contener la cuenta remitente. `MAIL_PASSWORD` contiene una contrasena de aplicacion de Google, no la contrasena normal de la cuenta.

La contrasena de aplicacion se genera en la cuenta de Google con verificacion en dos pasos habilitada. Se introduce directamente en el `.env` de la instalacion. Nunca se guarda en Git, capturas, incidencias ni ejemplos.

Para comprobar el envio, solicita recuperacion de contrasena para una cuenta registrada, recibe el mensaje y abre el enlace. El enlace se genera con `APP_URL`: debe apuntar a la instalacion desde la que se solicita la recuperacion. La verificacion de correo no es obligatoria para ingresar.

## Local y produccion

En local puedes usar HTTP con `SESSION_SECURE_COOKIE=false`. En produccion configura `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://tu-dominio` y `SESSION_SECURE_COOKIE=true`. No cambies estos valores locales solo para conseguir que una comprobacion de produccion aparezca en verde.

Cada instalacion tiene su propia base y sus credenciales. Conserva la `APP_KEY` de una instalacion existente, tambien al restaurar sus datos cifrados. Solo genera una clave nueva al crear una instalacion nueva; guarda esa clave por separado en un lugar privado.

## Plan y soporte

`VENDEFACIL_FREE_PRODUCT_LIMIT` establece el limite gratuito y `VENDEFACIL_UNLIMITED_PRODUCTS_PRICE` el importe. El plan actual es un pago unico por tienda, sin renovacion mensual ni vencimiento programado; se activa mediante revision administrativa.

`VENDEFACIL_SUPPORT_WHATSAPP` es el numero publico de contacto con codigo de pais. `VENDEFACIL_SUPPORT_EMAIL` permite publicar un correo de soporte opcional; no se usa automaticamente el Gmail privado de envio. No publiques un correo o telefono que no quieras mostrar a los visitantes.

Consulta [LANZAMIENTO.md](LANZAMIENTO.md) y [RESPALDOS.md](RESPALDOS.md) antes de instalar la version en el servidor definitivo.
