<?php

declare(strict_types=1);

namespace Spreadsheet\Enum;

use Spreadsheet\Exception\Enum\EnumException;

trait BaseEnumTrait
{
    /**
     * @return string[]
     */
    public static function allValues(): array
    {
        return array_map(fn ($enumCase) => $enumCase->value, static::cases());
    }

    /**
     * @param string[] $values
     *
     * @phpstan-ignore-next-line
     */
    public static function fromValues(array $values): array
    {
        try {
            return array_map(
                fn (string $allowedSourceHandle) => static::from($allowedSourceHandle),
                $values
            );
        } catch (\ValueError) {
            throw EnumException::create(sprintf(
                'Provided enum value does not exist, available options: %s',
                implode(',', self::allValues()),
            ));
        }
    }
}
