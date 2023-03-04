<?php

use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('files')->group(function (){
        Route::middleware('owner.file')->group(function () {
            Route::get('/{fileId}', [FileController::class, 'get']);
            Route::delete('/{fileId}', [FileController::class, 'destroy']);
            Route::put('/{fileId}', [FileController::class, 'update']);
        });
        Route::middleware('owner.folder')->group(function () {
            Route::post('/upload/{folderId}', [FileController::class, 'store']);
        });
    });

    Route::get('/test', function (){
        return Storage::disk('cloud')->download(\App\Models\File::find(4)->getPath());
    });

});
