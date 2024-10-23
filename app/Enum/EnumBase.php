<?php

namespace FalconBaseServices\Enum;

trait EnumBase
{
    public static function accept($value): bool
    {
        foreach (static::cases() as $case) {
            if (isset($case->value)) {
                if ($case->value == $value) {
                    return true;
                }
            } elseif ($case->name == $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate an array of options with value and label for select inputs.
     *
     * @return array
     */
    public function options(): array
    {
        return array_map(fn($case)
            => [
            'label' => $case->label(),
            'value' => $case->value ?? null,
            'translate' => method_exists($this, 'translate') ? $case->translate() : null,
        ], self::cases());
    }

    public function label(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
    }
}