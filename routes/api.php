<?php

use App\Http\Controllers\Api\PustakawanController;
use Illuminate\Support\Facades\Route;

Route::get('/pustakawan', [PustakawanController::class, 'index'])
    ->name('api.pustakawan.index');
