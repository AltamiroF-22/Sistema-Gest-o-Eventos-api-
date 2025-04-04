<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventsExportExcelController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Usuários
Route::post('/create-user', [UserController::class, 'store']); // Criar usuário
Route::get('/users', [UserController::class, 'index']); // Listar usuários

// Autenticação
Route::post('/login', [LoginController::class, 'login']); // Login

// Eventos
Route::get('/events', [EventController::class, 'index']); // Listar todos os eventos
Route::get('/events/{event}', [EventController::class, 'show']); // Detalhes de um evento
Route::get('/export-events', [EventsExportExcelController::class, 'export']);


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Requer Autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Autenticação
    Route::post('/logout/{user}', [LoginController::class, 'logout']); // Logout

    // Eventos (CRUD)
    Route::post('/create-event', [EventController::class, 'store']); // Criar evento
    Route::put('/events/{id}', [EventController::class, 'update']); // Atualizar evento
    Route::delete('/events/{id}', [EventController::class, 'destroy']); // Excluir evento

    // Inscrições
    Route::get('/user/registrations', [RegistrationController::class, 'getUserRegistrations']); // Eventos em que o usuário está inscrito
    Route::get('/user/events', [RegistrationController::class, 'getUserEvents']); // Eventos criados pelo usuário
    Route::post('/subscribe/{event}', [RegistrationController::class, 'registerEvent']); // Inscrever-se em um evento
    Route::post('/unsubscribe/{event}', [RegistrationController::class, 'unsubEvent']); // Cancelar inscrição em um evento

});