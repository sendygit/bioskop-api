<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SnacksController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/studio',StudioController::class);
    //Route::resource('/profile',ProfileController::class)->except(['create', 'store', 'edit', 'destroy']);
    //Profile User
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/{id_user}', [ProfileController::class, 'show']);
    Route::put('/update-profile/{id_user}', [ProfileController::class, 'updateProfile']);
    Route::post('/update-photo/{id_user}', [ProfileController::class, 'updatePhoto']);
    //SNACK
    Route::get('/kategori-snacks', [SnacksController::class, 'listKategori']);
    Route::get('/list-snacks', [SnacksController::class, 'listSnacks']);
    //FILM
    Route::get('/list-film', [FilmController::class, 'listFilm']);
    Route::get('/list-genre', [FilmController::class, 'listGenre']);
    Route::get('/detail-film', [FilmController::class, 'detailFilm']);
    Route::get('/production-house', [FilmController::class, 'ph']);
    //PAYMENT
    Route::get('/payment-method', [PaymentController::class, 'paymentMethod']);
    Route::get('/payment-status', [PaymentController::class, 'paymentStatus']);
    
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);