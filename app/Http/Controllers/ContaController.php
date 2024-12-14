<?php

namespace App\Http\Controllers;

use App\Helpers\ValidatorHelper;
use App\Http\Requests\ContaRequest;
use App\Models\Conta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContaController extends Controller
{
    public function criarConta(Request $request)
    {
        $validationResponse = ValidatorHelper::validate($request, new ContaRequest());

        if ($validationResponse !== true) {
            return $validationResponse;
        }

        try {
            $conta = new Conta();
            $conta->numero = $request->input('numero');
            $conta->saldo = $request->input('saldo');
            $conta->limite_credito = $request->input('limite_credito');
            $conta->save();

            Log::info('conta criada com sucesso', ['conta' => $conta->toArray()]);

            return response()->json(['message' => 'Conta criada com sucesso!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar conta', 'detalhes do erro.:' => $e->getMessage()], 500);
        }
    }

}
