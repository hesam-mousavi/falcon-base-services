<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWidth implements ValidationRule
{
    private string $max_width;

    public function __construct(int $max_width)
    {
        $this->max_width = $max_width;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            $filename = $item['tmp_name'];
            [$width, $height] = @getimagesize($filename);

            if ($width > $this->max_width) {
                $valid = false;
            }
        }

        if (!$valid) {
            $fail('validation.media.max_width')->translate(
                ['max' => $this->max_width],
            );
        }
    }
}
