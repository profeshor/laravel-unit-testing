<?php

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
use App\Http\Controllers\VideosController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\PlayslistsVideosController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('videos/{video}',[VideosController::class, 'get']);
Route::get('videos/',[VideosController::class, 'index']);
Route::get('playlists/',[PlaylistsController::class, 'index']);
Route::get('playlists/{playlist}/videos',[PlayslistsVideosController::class, 'index']);
