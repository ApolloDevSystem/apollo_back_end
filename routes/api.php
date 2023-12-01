<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnderecoController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('clientes', ClienteController::class);

Route::apiResource('funcionarios', FuncionarioController::class);
Route::get('/funcionarios', [FuncionarioController::class, 'index']);
Route::post('/criar-funcionario', [FuncionarioController::class, 'store']);
Route::get('/funcionario/{id}', [FuncionarioController::class, 'show']);
Route::put('/atualizar-funcionario/{id}', [FuncionarioController::class, 'update']);
Route::delete('/deletar-funcionario/{id}', [FuncionarioController::class, 'destroy']);
Route::get('/funcionarioCpf/{cpf}', [FuncionarioController::class, 'buscaPorCpf']);

Route::apiResource('usuarios', UserController::class);
Route::get('/usuarios', [UserController::class, 'index']);
Route::post('/criar-usuario', [UserController::class, 'store']);
Route::get('/usuario/{id}', [UserController::class, 'show']);
Route::put('/usuario/{id}', [UserController::class, 'update']);
Route::delete('/usuario/{id}', [UserController::class, 'destroy']);

Route::apiResource('enderecos', EnderecoController::class);
Route::get('/enderecos', [EnderecoController::class, 'index']);
Route::post('/criar-endereco', [EnderecoController::class, 'store']);
Route::get('/endereco/{id}', [EnderecoController::class, 'show']);
Route::put('/endereco/{id}', [EnderecoController::class, 'update']);
Route::delete('/endereco/{id}', [EnderecoController::class, 'destroy']);
