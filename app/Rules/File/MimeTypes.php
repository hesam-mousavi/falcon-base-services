<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MimeTypes implements ValidationRule
{
    private string|array $mime_types;

    public function __construct(array|string $mime_types)
    {
        $this->mime_types = $mime_types;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            if (!isset($item['mime_type'])) {
                $valid = false;
            } else {
                $mime = $item['mime_type'];

                if (
                    (
                        is_array($this->mime_types)
                        && !in_array($mime, $this->mime_types)
                    )
                    || (
                        !is_array($this->mime_types)
                        && $mime != $this->mime_types
                    )
                ) {
                    $valid = false;
                }
            }
        }

        if (!$valid) {
            $fail('validation.media.mime')->translate(
                [
                    'mimes' => is_array($this->mime_types) ? implode(', ', $this->mime_types) : $this->mime_types,
                ],
            );
        }
    }
}
