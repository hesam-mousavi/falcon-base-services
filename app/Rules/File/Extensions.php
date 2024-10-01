<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Extensions implements ValidationRule
{
    private string|array $extensions;

    public function __construct(array|string $extensions)
    {
//        $extensions_example = [
//            'image/jpeg' => ['jpg', 'jpeg'],
//            'image/png' => ['png'],
//            'video/mp4' => ['mp4'],
//        ];

        $this->extensions = $extensions;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            if (!isset($item['name'])) {
                $valid = false;
            } else {
                $filename = $item['name'];
                $extension = last(explode('.', $filename));

                if (!$this->acceptExtension($extension)) {
                    $valid = false;
                }
            }
        }

        if (!$valid) {
            $fail('validation.media.extension')->translate(
                [
                    'extensions' => is_array($this->extensions) ? $this->acceptedExtensionList() : $this->extensions,
                ],
            );
        }
    }

    private function acceptExtension(string $e): bool
    {
        if (is_array($this->extensions)) {
            foreach ($this->extensions as $extension) {
                if (is_array($extension)) {
                    foreach ($extension as $ext) {
                        if ($ext == $e) {
                            return true;
                        }
                    }
                } elseif ($extension == $e) {
                    return true;
                }
            }
        } elseif ($this->extensions == $e) {
            return true;
        }

        return false;
    }

    private function acceptedExtensionList(): string
    {
        $str_extensions = '';
        $counter = 0;

        if (is_array($this->extensions)) {
            foreach ($this->extensions as $extension) {
                if (is_array($extension)) {
                    foreach ($extension as $item) {
                        $str_extensions .= $item;
                        $counter++;
                        if ($counter <= count($this->extensions)) {
                            $str_extensions .= ', ';
                        }
                    }
                } else {
                    $str_extensions .= $item;
                }
            }
        } else {
            $str_extensions .= $this->extensions;
        }

        return $str_extensions;
    }

}
