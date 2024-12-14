<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorHelper
{
    public static function validate(Request $request, object $rulesObject)
    {
        $validator = Validator::make($request->all(), $rulesObject->rules(), [], $rulesObject->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        return true;
    }
}
