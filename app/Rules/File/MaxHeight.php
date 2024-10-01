<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxHeight implements ValidationRule
{
    private string $max_height;

    public function __construct(int $max_height)
    {
        $this->max_height = $max_height;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;

        foreach ($value as $item) {
            $filename = $item['tmp_name'];
            [$width, $height] = @getimagesize($filename);

            if ($height > $this->max_height) {
                $valid = false;
            }
        }

        if (!$valid) {
            $fail('validation.media.max_height')->translate(
                ['max' => $this->max_height],
            );
        }
    }
}
