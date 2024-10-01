<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinHeight implements ValidationRule
{
    private string $min_height;

    public function __construct(int $min_height)
    {
        $this->min_height = $min_height;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            $filename = $item['tmp_name'];
            [$width, $height] = @getimagesize($filename);

            if ($height < $this->min_height) {
                $valid = false;
            }
        }

        if (!$valid) {
            $fail('validation.media.min_height')->translate(
                ['min' => $this->min_height],
            );
        }
    }
}
