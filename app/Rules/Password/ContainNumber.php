<?php

namespace FalconBaseServices\Rules\Password;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value && !preg_match('/\pN/u', $value)) {
            $fail('validation.password.contain_number')->translate();
        }
    }
}
