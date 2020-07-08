<?php

namespace App\Http\Requests\Api;

/**
 * @property $code
 * @property $username
 * @property $password
 */
class WeappAuthorizationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'required|string',
        ];
    }
}
