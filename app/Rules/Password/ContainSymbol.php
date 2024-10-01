<?php

namespace FalconBaseServices\Rules\Password;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainSymbol implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value && !preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)) {
            $fail('validation.password.contain_symbol')->translate();
        }
    }
}
