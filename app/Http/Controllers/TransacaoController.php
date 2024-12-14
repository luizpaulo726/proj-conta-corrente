<?php

namespace App\Http\Controllers;

use App\Helpers\ValidatorHelper;
use App\Http\Requests\DepositarRequest;
use App\Http\Requests\ProcessarLoteRequest;
use App\Http\Requests\TransferirRequest;
use App\Models\Conta;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransacaoController extends Controller
{

    public function depositar(Request $request, $numeroConta)
    {
        $validationResponse = ValidatorHelper::validate($request, new DepositarRequest());

        if ($validationResponse !== true) {
            return $validationResponse;
        }

        $conta = Conta::where('numero', $numeroConta)->first();

        if (!$conta) {
            return response()->json(['error' => 'Conta não encontrada.'], 404);
        }

        DB::transaction(function () use ($request, $conta) {
            $transacao = Transacao::create([
                'conta_id' => $conta->id,
                'tipo' => 'deposito',
                'valor' => $request->valor,
                'descricao' => $request->descricao ?? 'Depósito realizado'
            ]);

            $conta->update([
                'saldo' => $conta->saldo + $request->valor
            ]);

            Log::info('Depósito realizado', [
                'transacao' => $transacao->toArray()
            ]);
        });

        return response()->json(['message' => 'Depósito realizado com sucesso.']);
    }

    public function sacar(Request $request, $numeroConta)
    {
        $validationResponse = ValidatorHelper::validate($request, new DepositarRequest());

        if ($validationResponse !== true) {
            return $validationResponse;
        }

        $conta = Conta::where('numero', $numeroConta)->first();

        if (!$conta) {
            return response()->json(['error' => 'Conta não encontrada.'], 404);
        }

        $valorTotal = $conta->saldoDisponivel();
        $valorComTaxa = $request->valor + ($request->valor * 0.03); // Aplicar a taxa de 3%

        if ($valorComTaxa > $valorTotal) {
            return response()->json(['error' => 'Saldo insuficiente.'], 422);
        }

        DB::transaction(function () use ($request, $numeroConta, $conta, $valorComTaxa) {

            $conta->update(['saldo' => $conta->saldo - $valorComTaxa]);

            $transacao = Transacao::create([
                'conta_id' => $conta->id,
                'tipo' => 'saque',
                'valor' => -$valorComTaxa, // Valor com a taxa
                'descricao' => $request->descricao ?? 'Saque realizado'
            ]);

            // Registro de log
            Log::info('Saque realizado com taxa', ['transacao' => $transacao->toArray()]);
        });

        return response()->json(['message' => 'Saque realizado com sucesso.']);
    }


    public function transferir(Request $request)
    {
        $validationResponse = ValidatorHelper::validate($request, new TransferirRequest());

        if ($validationResponse !== true) {
            return $validationResponse;
        }

        $contaOrigem = Conta::where('numero', $request->numeroContaOrigem)->first();
        $contaDestino = Conta::where('numero', $request->numeroContaDestino)->first();

        if (!$contaOrigem) {
            return response()->json(['error' => 'Conta origem não encontrada.'], 404);
        }

        if (!$contaDestino) {
            return response()->json(['error' => 'Conta destino não encontrada.'], 404);
        }

        $valorComTaxa = $request->valor + ($request->valor * 0.03); // apliquei taxa de taxa de 3%

        if ($valorComTaxa > $contaOrigem->saldoDisponivel()) {
            return response()->json(['error' => 'Saldo insuficiente da conta origem.'], 400);
        }

        DB::transaction(function () use ($request, $contaOrigem, $contaDestino, $valorComTaxa) {

            $contaOrigem->update(['saldo' => $contaOrigem->saldo - $valorComTaxa]);
            $contaDestino->update(['saldo' => $contaDestino->saldo + $request->valor]);

            Transacao::create([
                'conta_id' => $contaOrigem->id,
                'tipo' => 'transferencia',
                'valor' => -$valorComTaxa, // Valor com a taxa
                'descricao' => $request->descricao ?? 'Transferência realizada para conta ' . $request->numeroContaDestino
            ]);

            Transacao::create([
                'conta_id' => $contaDestino->id,
                'tipo' => 'transferencia',
                'valor' => $request->valor, // Valor original
                'descricao' => $request->descricao ?? 'Transferência recebida da conta ' . $request->numeroContaOrigem
            ]);

            Log::info('Transferência realizada com taxa', [
                'contaOrigem' => $contaOrigem->numero,
                'contaDestino' => $contaDestino->numero,
                'valorOriginal' => $request->valor,
                'valorComTaxa' => $valorComTaxa
            ]);
        });

        return response()->json(['message' => 'Transferência realizada com sucesso.']);
    }

    public function processarLote(Request $request)
    {

        $validationResponse = ValidatorHelper::validate($request, new ProcessarLoteRequest());

        if ($validationResponse !== true) {
            return $validationResponse;
        }

        DB::transaction(function () use ($request) {
            foreach ($request->transacoes as $transacao) {
                try {
                    switch ($transacao['tipo']) {
                        case 'deposito':
                            $this->depositar(new Request($transacao), $transacao['numero_origem']);
                            break;
                        case 'saque':
                            $this->sacar(new Request($transacao), $transacao['numero_origem']);
                            break;
                        case 'transferencia':
                            $this->transferir(new Request($transacao));
                            break;
                        default:
                            throw new \Exception('Tipo de transação inválido.');
                    }
                } catch (\Exception $e) {
                    Log::error('Erro ao processar transação', ['transacao' => $transacao, 'erro' => $e->getMessage()]);
                    throw $e;
                }
            }
        });

        return response()->json(['message' => 'Lote processado com sucesso.']);
    }

}
