<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mime\MessageConverter;

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
Route::post('/send-message',[MessageController::class,'sendMessage']);
Route::get('/messages',[MessageController::class,'fetchMessage']);
Route::get('/messages/{FriendId}',[MessageController::class,'getUserMessage']);
Route::get('/private',[MessageController::class,'privateChat']);
Route::get('/get-users',[MessageController::class,'getUsers']);
Route::get('/private-message/{user}',[MessageController::class,'getUserMessage']);
Route::post('/private-message/{user}',[MessageController::class,'sendPrivateMessage']);
Auth::routes();
