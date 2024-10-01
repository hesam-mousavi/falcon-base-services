<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxSize implements ValidationRule
{
    private string $max_size;
    private string $type;

    public function __construct(int $max_size, string $type = 'mb')
    {
        $this->max_size = $max_size;
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            if (!isset($item['size'])) {
                $valid = false;
            } else {
                $size = $item['size'];

                if (
                    ($this->type == 'kb' && ($size / 1024) > $this->max_size)
                    || (
                        $this->type == 'mb'
                        && ($size / 1024) / 1024 > $this->max_size
                    )
                ) {
                    $valid = false;
                }
            }
        }

        if (!$valid) {
            $fail('validation.media.max_size')->translate(['max' => ($this->max_size), 'type' => $this->type]);
        }
    }
}
