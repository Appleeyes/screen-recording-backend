<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/upload', [VideoController::class, 'upload']);
    Route::post('/upload_chunk', [VideoController::class, 'uploadChunk']);
    Route::get('/play/{video}', [VideoController::class, 'play']);
    Route::get('/list', [VideoController::class, 'videoList']);
});
