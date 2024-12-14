<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositarRequest extends FormRequest
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
            'valor' => 'required|numeric|min:0.01'
        ];
    }
}
