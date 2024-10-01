<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsImage implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;

        foreach ($value as $item) {
            if (!isset($item['tmp_name'])) {
                $valid = false;
            } else {
                $filename = $item['tmp_name'];
                [$width, $height] = @getimagesize($filename);

                if (empty($width) && empty($height)) {
                    $valid = false;
                }
            }
        }

        if (!$valid) {
            $fail('validation.image')->translate();
        }
    }
}
