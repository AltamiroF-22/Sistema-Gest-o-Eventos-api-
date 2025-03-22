<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/create-user', [UserController::class, 'store']); // POST http://127.0.0.1:8000/api/create-user