# Respaldos y recuperacion

## Crear y comprobar una copia

```bash
php artisan vendefacil:backup
php artisan vendefacil:backup-verify "ruta/al/respaldo.zip"
```

La copia contiene la base MySQL o SQLite, las imagenes del disco publico y los archivos privados, incluidos los comprobantes. Usa SHA-256 para comprobar todos los archivos antes de declarar el respaldo correcto.

Los ZIP se guardan en `storage/app/private/backups`. Se excluyen `.env`, archivos ocultos, los propios respaldos y la carpeta temporal del proceso. Los archivos no se descargan desde la web. Los ZIP contienen datos personales: no los subas a Git ni a carpetas publicas.

Conserva la `APP_KEY` original por separado; no se incluye en el ZIP. Un respaldo en el mismo disco protege frente a algunos errores de datos, pero no frente a la perdida de ese disco. Copia periodicamente los ZIP a un almacenamiento privado externo de tu eleccion.

Para MySQL deben existir `mysqldump` y `mysql`. En XAMPP se detectan sus rutas habituales. En otros entornos puedes indicar `MYSQLDUMP_PATH` y `MYSQL_PATH`. Las credenciales se pasan mediante un archivo temporal privado, nunca como argumentos visibles del proceso. La cuenta de MySQL debe tener permisos para leer los datos y, al restaurar, crear o reemplazar sus tablas.

## Programacion diaria

La aplicacion programa un respaldo a las 03:00, hora de Lima. `BACKUP_TIME`, `BACKUP_TIMEZONE` y `BACKUP_KEEP_DAYS` permiten cambiar la hora, la zona y la retencion; el valor predeterminado conserva 14 dias. Los ZIP antiguos del proyecto se retiran solamente despues de crear una copia nueva correctamente.

El programador de Laravel necesita estar activo en el servidor. En un servidor Linux configura una entrada de cron como esta, con las rutas reales:

```cron
* * * * * cd /ruta/vendefacil && /usr/bin/php artisan schedule:run >> /ruta/privada/vendefacil-scheduler.log 2>&1
```

En Windows crea una tarea del Programador de tareas que cada minuto ejecute PHP con el argumento `C:\ruta\vendefacil\artisan schedule:run`. Para desarrollo puedes mantener `php artisan schedule:work` en una terminal.

Comprueba la programacion con `php artisan schedule:list`. Tener el evento en la lista no significa que el cron o la tarea del sistema ya esten activos. El servidor definitivo aun debe configurar ese proceso.

## Restaurar

Hazlo desde consola, en una ventana de mantenimiento y con acceso exclusivo a la base. Deten tambien los workers o tareas que puedan escribir datos.

```bash
php artisan down
php artisan vendefacil:backup-verify "ruta/al/respaldo.zip"
php artisan vendefacil:restore "ruta/al/respaldo.zip" --force
php artisan optimize:clear
# Comprueba inicio de sesion, tienda, imagenes y comprobantes antes de reabrir.
php artisan up
```

La restauracion exige `--force`; en produccion exige ademas el modo mantenimiento. Antes de modificar datos crea otra copia del estado actual. Comprueba el manifiesto y las rutas del ZIP, restaura la base configurada y copia los archivos presentes en el respaldo. No elimina archivos adicionales que no esten en el ZIP. La copia debe usar el mismo motor de base de datos que la instalacion de destino.

Si algo falla, el comando no cambia el mantenimiento. Conserva la copia previa y revisa el registro privado antes de reabrir. No ejecutes comandos de restauracion desde una URL publica.

## Pruebas de recuperacion

Las pruebas automaticas restauran una base SQLite y sus archivos de prueba. Tambien existe una prueba MySQL real, habilitada con `VENDEFACIL_MYSQL_BACKUP_TEST=1`, que crea y elimina exclusivamente una base temporal `vendefacil_backup_test_*`. Nunca restaura sobre la base de trabajo. El flujo de GitHub incluye un servidor MySQL temporal para ejecutar esa prueba.
