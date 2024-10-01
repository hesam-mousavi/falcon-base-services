<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinWidth implements ValidationRule
{
    private string $min_width;

    public function __construct(int $min_width)
    {
        $this->min_width = $min_width;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            $filename = $item['tmp_name'];
            [$width, $height] = @getimagesize($filename);

            if ($width < $this->min_width) {
                $valid = false;
            }
        }

        if (!$valid) {
            $fail('validation.media.min_width')->translate(
                ['min' => $this->min_width],
            );
        }
    }
}
