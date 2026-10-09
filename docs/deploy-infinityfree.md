# Despliegue en InfinityFree

InfinityFree no permite usar SSH, Composer, npm ni `php artisan` en el servidor gratis. Por eso VendeFacil debe prepararse localmente y luego subirse completo por FTP al directorio `htdocs`.

## 1. Preparar el proyecto en tu PC

Desde Git Bash o PowerShell:

```bash
cd ~/vendefacil
composer install --no-dev --prefer-dist --no-interaction
composer dump-autoload --no-dev
npm install
npm run build
php artisan optimize:clear
php artisan migrate
```

No subas `node_modules`. Si vas a subir por FTP desde la carpeta del proyecto, simplemente ignora esa carpeta.

## 2. Base de datos

En InfinityFree:

1. Entra al panel.
2. Crea una base de datos MySQL.
3. Copia estos datos: host, database, username y password.
4. En phpMyAdmin de InfinityFree importa una copia de tu base local.

Como en InfinityFree no puedes ejecutar migraciones con `php artisan migrate`, debes importar la base ya creada.

Si hiciste cambios nuevos como pagos, productos ilimitados o tiendas destacadas, primero ejecuta las migraciones en tu PC y luego vuelve a exportar/importar la base de datos completa en InfinityFree.

## 3. Archivo `.env`

En el servidor debe existir un archivo `.env`.

Puedes copiar `.env.infinityfree.example`, renombrarlo como `.env` y completar:

```env
APP_KEY=tu_app_key_local
APP_URL=https://tu-dominio.infinityfreeapp.com
DB_HOST=sqlXXX.infinityfree.com
DB_DATABASE=if0_XXXXXXX_vendefacil
DB_USERNAME=if0_XXXXXXX
DB_PASSWORD=tu_password_mysql
```

Usa la misma `APP_KEY` que tienes en tu `.env` local para evitar problemas con sesiones y datos cifrados.

Si todavia no tienes SSL activo, cambia temporalmente:

```env
SESSION_SECURE_COOKIE=false
```

Cuando uses HTTPS, vuelve a:

```env
SESSION_SECURE_COOKIE=true
```

## 4. Archivos a subir

Sube el contenido del proyecto completo dentro de `htdocs`:

```text
htdocs/
  app/
  bootstrap/
  config/
  database/
  public/
  resources/
  routes/
  storage/
  vendor/
  .env
  .htaccess
  artisan
  composer.json
  composer.lock
```

No subas:

```text
node_modules/
.git/
storage/logs/*.log
storage/framework/views/*.php
```

El archivo `.htaccess` de la raiz es importante. Hace que InfinityFree sirva Laravel desde `public` aunque el hosting apunte a `htdocs`.

## 5. Imagenes subidas por usuarios

VendeFacil usa `storage/app/public` para logos y productos. InfinityFree no soporta symlinks como `php artisan storage:link`, por eso el `.htaccess` de raiz incluye esta regla:

```apache
RewriteRule ^storage/(.*)$ /storage/app/public/$1 [END]
```

Eso permite que URLs como `/storage/products/foto.jpg` o `/storage/payment-proofs/comprobante.pdf` carguen desde `storage/app/public/...`.

## 6. Si sale error 500

Revisa en este orden:

1. Que `.env` exista en `htdocs`.
2. Que `APP_KEY` no este vacio.
3. Que `vendor/` este subido.
4. Que `public/.htaccess` exista.
5. Que el `.htaccess` de la raiz exista.
6. Que los datos MySQL sean exactamente los de InfinityFree.
7. Que `APP_DEBUG=false`; si necesitas diagnosticar, ponlo temporalmente en `true` y luego vuelve a `false`.

## 7. Flujo recomendado para probar

1. Sube el proyecto.
2. Importa la base de datos.
3. Abre la URL principal.
4. Prueba registro/login.
5. Prueba crear tienda.
6. Prueba subir logo o producto.
7. Prueba abrir la imagen subida desde `/storage/...`.
8. Prueba subir un comprobante en `Plan y pagos`.
9. Entra como admin a `Administración > Pagos` y aprueba el comprobante.

## 8. SEO después de publicar

Cuando la web ya abra con tu dominio real:

1. Confirma que `APP_URL` tenga tu dominio con `https://`.
2. Abre `/robots.txt` y revisa que muestre el sitemap.
3. Abre `/sitemap.xml` y revisa que liste la portada y las tiendas activas.
4. Entra a Google Search Console, agrega tu dominio y envía el sitemap:

```text
https://tu-dominio.infinityfreeapp.com/sitemap.xml
```

Google no indexa de inmediato. Lo normal es que tarde días o semanas, pero el sitio queda listo para que pueda rastrear títulos, descripciones, canonicals, Open Graph y datos estructurados.
