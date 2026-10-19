<?php

return [
    'path' => storage_path('app/private/backups'),
    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 14),
    'time' => env('BACKUP_TIME', '03:00'),
    'timezone' => env('BACKUP_TIMEZONE', 'America/Lima'),
    'mysqldump' => env('MYSQLDUMP_PATH', PHP_OS_FAMILY === 'Windows' ? 'C:/xampp/mysql/bin/mysqldump.exe' : 'mysqldump'),
    'mysql' => env('MYSQL_PATH', PHP_OS_FAMILY === 'Windows' ? 'C:/xampp/mysql/bin/mysql.exe' : 'mysql'),
];
