<?php

use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/view-pdf/{filename}', [App\Http\Controllers\PDFController::class, 'displayPDF']);
Route::post('/upload-pdf', [App\Http\Controllers\PDFController::class, 'uploadPDF']);

Route::post('/upload-video', [VideoController::class, 'uploadVideo']);
Route::get('/watch-video/{filename}', [VideoController::class, 'watchVideo']);
