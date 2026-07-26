<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('pengingat:shift siang')
    ->days([6, 0, 1, 2, 3, 4])
    ->dailyAt('13:30');

Schedule::command('pengingat:shift-tambahan siang')
    ->days([6, 0, 1, 2, 3, 4])
    ->dailyAt('14:00');

Schedule::command('laporan:shift siang')
    ->days([6, 0, 1, 2, 3, 4])
    ->dailyAt('14:30');

Schedule::command('pengingat:shift malam')
    ->days([5, 6, 0, 1, 2, 3])
    ->dailyAt('20:30');

Schedule::command('pengingat:shift-tambahan malam')
    ->days([5, 6, 0, 1, 2, 3])
    ->dailyAt('21:00');

Schedule::command('laporan:shift malam')
    ->days([5, 6, 0, 1, 2, 3])
    ->dailyAt('21:30');

Schedule::command('absen:bersihkan-foto')->daily();

