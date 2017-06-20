<?php

namespace App\Http\Requests\Secret;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expire_sec' => 'required|integer|min:1',
            'secret' => 'required|filled',
            'password' => 'required|filled',
            'ip' => 'ip'
        ];
    }
}