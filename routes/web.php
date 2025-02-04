<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NameParsingController;

Route::post('/parse-file', [NameParsingController::class, 'parseFile']);

Route::get('/', function () {
    return view('app');
});

