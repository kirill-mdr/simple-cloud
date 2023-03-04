<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\SharedFileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::prefix('users')->group(function () {
        Route::get('/info', [UserController::class, 'userInfo']);
    });

    Route::prefix('files')->group(function (){
        Route::middleware('owner.file')->group(function () {
            Route::get('/{fileId}', [FileController::class, 'get']);
            Route::delete('/{fileId}', [FileController::class, 'destroy']);
            Route::put('/{fileId}', [FileController::class, 'update']);
        });
    });

    Route::prefix('folders')->group(function () {
        Route::middleware('owner.folder')->group(function () {
            Route::get('/{folderId}', [FolderController::class, 'get']);
            Route::post('/{folderId}', [FolderController::class, 'store']);
            Route::delete('/{folderId}', [FolderController::class, 'destroy']);
            Route::post('/{folderId}/upload-file', [FolderController::class, 'uploadFile']);
        });
    });

    Route::prefix('shared-files')->group(function (){
        Route::middleware('owner.file')->group(function () {
            Route::post('/{fileId}', [SharedFileController::class, 'store']);
            Route::delete('/{fileId}', [SharedFileController::class, 'destroy']);
        });
    });

});
