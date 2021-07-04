<?php

namespace App\Http\Requests\Api;

/**
 * @property $content
 */
class ReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'content' => 'required|min:2',
        ];
    }
}