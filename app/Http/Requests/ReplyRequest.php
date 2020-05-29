<?php

namespace App\Http\Requests;

/**
 * @property $content
 * @property $topic_id
 */
class ReplyRequest extends Request
{
    public function rules()
    {
        return [
            'content' => 'required|min:2',
        ];
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }
}
