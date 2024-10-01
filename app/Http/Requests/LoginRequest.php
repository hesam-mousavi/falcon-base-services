<?php

namespace FalconBaseServices\Http\Requests;

use FalconBaseServices\FormRequest;
use FalconBaseServices\Rules\Password\containLetter;

class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'password' => ['required', 'min:6', new ContainLetter()],
            'email' => ['required', 'email'],
            'remember' => ['nullable', 'boolean'],
        ];
    }
}
