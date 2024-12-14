<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\TransacaoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//// Rotas para contas
//Route::post('/contas', [ContaController::class, 'criarConta']);
//Route::post('/contas/{numero}/depositar', [TransacaoController::class, 'depositar']);
//Route::post('/contas/{numero}/sacar', [TransacaoController::class, 'sacar']);
//Route::post('/contas/transferir', [TransacaoController::class, 'transferir']);

Route::prefix('contas')->group(function () {
    Route::post('/', [ContaController::class, 'criarConta']);
    Route::post('{numero}/depositar', [TransacaoController::class, 'depositar']);
    Route::post('{numero}/sacar', [TransacaoController::class, 'sacar']);
    Route::post('transferir', [TransacaoController::class, 'transferir']);
    Route::post('processar-lote', [TransacaoController::class, 'processarLote']);
});
