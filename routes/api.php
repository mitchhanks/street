<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NameParsingController;

Route::post('/parse-names', [NameParsingController::class, 'parseFile']);
