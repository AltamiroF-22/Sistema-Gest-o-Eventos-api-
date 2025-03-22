<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rota para criação de usuário
Route::post('/create-user', [UserController::class, 'store']); // POST http://127.0.0.1:8000/api/create-user

// Rota para listar usuários
Route::get('/users', [UserController::class, 'index']); // GET http://127.0.0.1:8000/api/users

// Rota de login
Route::post('/login', [LoginController::class, 'login']); // POST http://127.0.0.1:8000/api/login

Route::get('/events', [EventController::class, 'index']); // POST http://127.0.0.1:8000/api/create-event


// Rotas privadas
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout/{user}', [LoginController::class, 'logout']); // POST http://127.0.0.1:8000/api/logout
    
    Route::post('/create-event', [EventController::class, 'store']); // POST http://127.0.0.1:8000/api/create-event
});