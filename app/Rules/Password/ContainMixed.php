<?php

namespace FalconBaseServices\Rules\Password;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainMixed implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value && !preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
            $fail('validation.password.contain_mixed')->translate();
        }
    }
}
