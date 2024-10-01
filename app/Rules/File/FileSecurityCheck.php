<?php

namespace FalconBaseServices\Rules\File;

use Closure;
use FalconBaseServices\Services\CurrentUser;
use Illuminate\Contracts\Validation\ValidationRule;

class FileSecurityCheck implements ValidationRule
{
    private mixed $file;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        foreach ($value as $item) {
            $this->file = $item;

            if (
                $this->moreThanOneExtension()
                || $this->containSomeExecutableExtensions()
                || $this->containNullByteInFileName()
                || $this->containBadFileName()
                || $this->containBadContent()
            ) {
                $valid = false;
            }
        }

        if (!$valid) {
            $fail('validation.not_secure')->translate();
        }
    }

    private function moreThanOneExtension(): bool
    {
        if (count(explode('.', $this->file['name'])) > 2) {
            LOGGER->alert(
                'user attempt to upload file with two extension',
                ['user' => CurrentUser::user(), 'file' => $this->file],
            );

            return true;
        }

        return false;
    }

    /**
     * extensions may still be allowed
     */
    private function containSomeExecutableExtensions(): bool
    {
        $executable_extensions = [
            'shtml',
            'phtml',
            'asa',
            'cer',
            'asax',
            'swf',
            'xap',
        ];

        foreach ($executable_extensions as $executable_extension) {
            if (str_contains($this->file['name'], '.'.$executable_extension)
            ) {
                LOGGER->alert(
                    'user attempt to upload file with executable extensions',
                    ['user' => CurrentUser::user(), 'file' => $this->file],
                );

                return true;
            }
        }

        return false;
    }

    /**
     * If the site is using file extension whitelists, this can often be
     * bypassed by adding %00 (HTML encoding) or \x00
     * (hex encoding) to the end of the file name. For example:
     * php-reverse-shell.php%00.gif
     */
    private function containNullByteInFileName(): bool
    {
        if (
            str_contains($this->file['name'], '%00')
            || str_contains($this->file['name'], '\x00')
        ) {
            LOGGER->alert(
                'user attempt to upload file with null byte',
                ['user' => CurrentUser::user(), 'file' => $this->file],
            );

            return true;
        }

        return false;
    }

    private function containBadFileName(): bool
    {
        $bad_files = [
            '..',
            '.git',
            '.htaccess',
            '.svn',
            'composer.json',
            'composer.lock',
            'framework_config.yaml',
        ];

        foreach ($bad_files as $bad_file) {
            if (str_contains($this->file['name'], $bad_file)) {
                LOGGER->alert(
                    'user attempt to upload file with bad file name',
                    ['user' => CurrentUser::user(), 'file' => $this->file],
                );

                return true;
            }
        }

        return false;
    }

    private function containBadContent(): bool
    {
        $bad_contents = ['<?php', 'eval', 'exec'];
        $content = file_get_contents($this->file['path']);

        foreach ($bad_contents as $bad_content) {
            if (str_contains($content, $bad_content)) {
                LOGGER->alert(
                    'user attempt to upload file with bad file name',
                    ['user' => CurrentUser::user(), 'file' => $this->file],
                );

                return true;
            }
        }

        return false;
    }

}
