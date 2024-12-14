<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessarLoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transacoes' => 'required|array',
            'transacoes.*.tipo' => 'required|in:deposito,saque,transferencia',
            'transacoes.*.numeroContaOrigem' => 'required_if:transacoes.*.tipo,transferencia|exists:contas,numero',
            'transacoes.*.numeroContaDestino' => 'required_if:transacoes.*.tipo,transferencia|exists:contas,numero',
            'transacoes.*.valor' => 'required|numeric|min:0.01',
            'transacoes.*.descricao' => 'nullable|string',
        ];
    }
}
