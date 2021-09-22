<?php

use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Telegram
 */
Route::group(['prefix' => 'bot', 'middleware' => 'web'], function () {
    Route::post('updates', [BotController::class, 'index']);
    Route::get('updates', [BotController::class, 'skip_update']);
});


