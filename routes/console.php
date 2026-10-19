<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('vendefacil:backup')->dailyAt(config('backups.time'))->timezone(config('backups.timezone'))->withoutOverlapping()->onFailure(function () {
    \Illuminate\Support\Facades\Log::error('Fallo el respaldo programado de VendeFacil.');
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
