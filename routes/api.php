<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AgendaController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DresscodeController;
use App\Http\Controllers\API\PegawaiController;

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

Route::post('register', [AuthController::class, 'register']); //POST /api/register : Membuat akun 
Route::post('login', [AuthController::class, 'login']); //POST /api/login : login 


    // Route::post('agendas', [AgendaController::class, 'add']); 
    // Route::get('agendas', [AgendaController::class, 'list']); 
    // Route::get('agendas/{id}', [AgendaController::class, 'detail']); 
    // Route::put('agendas/{id}', [AgendaController::class, 'edit']); 
    // Route::delete('agendas/{id}', [AgendaController::class, 'delete']); 

    // Route::get('users', [UserController::class, 'list']);
    // Route::post('users', [UserController::class, 'add']);
    // Route::get('users/{id}', [UserController::class, 'detail']);
    // Route::put('users/{id}', [UserController::class, 'edit']);
    // Route::delete('users/{id}', [UserController::class, 'delete']);

    // Route::get('dresscodes', [DresscodeController::class, 'list']);
    // Route::post('dresscodes', [DresscodeController::class, 'add']);
    // Route::get('dresscodes/{id}', [DresscodeController::class, 'detail']);
    // Route::put('dresscodes/{id}', [DresscodeController::class, 'edit']);
    // Route::delete('dresscodes/{id}', [DresscodeController::class, 'delete']);

    // Route::get('/agenda-by-date', [AgendaController::class, 'getAgendaByDateRange']);
    // Route::get('agendas-today', [AgendaController::class, 'getAgendaForToday']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('agendas', [AgendaController::class, 'add']); 
    Route::get('agendas', [AgendaController::class, 'list']); 
    Route::get('agendas/{id}', [AgendaController::class, 'detail']); 
    Route::put('agendas/{id}', [AgendaController::class, 'edit']); 
    Route::delete('agendas/{id}', [AgendaController::class, 'delete']); 
    Route::get('/agenda-by-date', [AgendaController::class, 'getAgendaByDateRange']);
    Route::get('agendas-today', [AgendaController::class, 'getAgendaForToday']);

    Route::get('users', [UserController::class, 'list']);
    Route::post('users', [UserController::class, 'add']);
    Route::get('users/{id}', [UserController::class, 'detail']);
    Route::put('users/{id}', [UserController::class, 'edit']);
    Route::delete('users/{id}', [UserController::class, 'delete']);

    Route::get('dresscodes', [DresscodeController::class, 'list']);
    Route::post('dresscodes', [DresscodeController::class, 'add']);
    Route::get('dresscodes/{id}', [DresscodeController::class, 'detail']);
    Route::put('dresscodes/{id}', [DresscodeController::class, 'edit']);
    Route::delete('dresscodes/{id}', [DresscodeController::class, 'delete']);

    Route::get('pegawais', [PegawaiController::class, 'list']);
    Route::post('pegawais', [PegawaiController::class, 'add']);
    Route::get('pegawais/{id}', [PegawaiController::class, 'detail']);
    Route::put('pegawais/{id}', [PegawaiController::class, 'edit']);
    Route::delete('pegawais/{id}', [PegawaiController::class, 'delete']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/docs', function () {
    return view('scribe.index');
});
