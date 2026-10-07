# VendeFacil

<p align="center">
  <strong>Marketplace para negocios locales, productos independientes y ventas directas por WhatsApp.</strong>
</p>

<p align="center">
  <a href="https://laravel.com"><img alt="Laravel" src="https://img.shields.io/badge/Laravel-12-ff2d20?style=for-the-badge&logo=laravel&logoColor=white"></a>
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777bb4?style=for-the-badge&logo=php&logoColor=white">
  <img alt="Tailwind CSS" src="https://img.shields.io/badge/Tailwind-CSS-38bdf8?style=for-the-badge&logo=tailwindcss&logoColor=white">
  <img alt="Vite" src="https://img.shields.io/badge/Vite-Frontend-646cff?style=for-the-badge&logo=vite&logoColor=white">
</p>

<p align="center">
  <img src="docs/images/vendefacil-home.png" alt="Vista principal de VendeFacil">
</p>

## Descripcion

VendeFacil es una aplicacion web tipo marketplace creada con Laravel. Su objetivo es ayudar a pequenos negocios a tener una presencia digital sencilla: crear una tienda, publicar productos y recibir consultas de compradores directamente por WhatsApp.

El proyecto incluye una experiencia publica para explorar productos y tiendas, y un panel privado para que cada vendedor administre su negocio.

## Caracteristicas

- Home publico con buscador de productos y tiendas.
- Filtros por categoria, ciudad y disponibilidad de delivery.
- Pagina publica para cada tienda con sus productos activos.
- Registro, login, recuperacion de contrasena y verificacion de correo.
- Panel para vendedores con administracion de tienda.
- CRUD de productos con imagen, precio, stock y categoria.
- Contacto directo por WhatsApp para tiendas y productos.
- Registro de clics a WhatsApp y visitas a tiendas.
- Panel de administracion para revisar, pausar o eliminar tiendas.
- Diseno responsive con Blade, Tailwind CSS y Alpine.js.

## Seguridad

El proyecto incluye varias medidas de seguridad aplicadas sobre la base de Laravel:

- Autenticacion con Laravel Breeze.
- Hash seguro de contrasenas.
- Proteccion CSRF en formularios.
- Limitacion de intentos en login, registro, reset de contrasena, zona privada y admin.
- Proteccion de rutas con `auth`, `verified` y middleware `admin`.
- Validacion de imagenes por tipo, peso y dimensiones.
- Proteccion contra asignacion masiva de campos sensibles como `is_admin`, `user_id`, `store_id` y `active`.
- Headers de seguridad: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `Content-Security-Policy`, COOP y CORP.
- Sesiones cifradas y cookies `HttpOnly`/`SameSite`.

## Tecnologias

- Laravel 12
- PHP 8.2
- MySQL o SQLite
- Laravel Breeze
- Blade
- Tailwind CSS
- Alpine.js
- Vite
- Composer
- NPM

## Instalacion Local

Clona el repositorio:

```bash
git clone https://github.com/Angel-ETECH/Vende-Facil.git
cd Vende-Facil
```

Instala dependencias de PHP:

```bash
composer install
```

Instala dependencias de frontend:

```bash
npm install
```

Crea el archivo de entorno:

```bash
cp .env.example .env
```

Genera la clave de Laravel:

```bash
php artisan key:generate
```

Configura tu base de datos en `.env`. Puedes usar MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vendefacil
DB_USERNAME=root
DB_PASSWORD=
```

Ejecuta migraciones y seeders:

```bash
php artisan migrate --seed
```

Crea el enlace para imagenes publicas:

```bash
php artisan storage:link
```

Compila los assets:

```bash
npm run build
```

Levanta el servidor local:

```bash
php artisan serve
```

Luego abre:

```text
http://127.0.0.1:8000
```

## Comandos Utiles

Ejecutar frontend en desarrollo:

```bash
npm run dev
```

Reconstruir assets para produccion:

```bash
npm run build
```

Limpiar caches de Laravel:

```bash
php artisan optimize:clear
```

Ver rutas:

```bash
php artisan route:list
```

## Estructura Principal

```text
app/
  Http/Controllers/
  Http/Middleware/
  Models/
database/
  migrations/
  seeders/
resources/
  views/
  css/
  js/
routes/
  web.php
  auth.php
docs/
  images/
```

## Autor

Desarrollado por **Angel ETECH** como proyecto de marketplace para pequenos negocios y ventas por WhatsApp.

Repositorio: [Angel-ETECH/Vende-Facil](https://github.com/Angel-ETECH/Vende-Facil)
