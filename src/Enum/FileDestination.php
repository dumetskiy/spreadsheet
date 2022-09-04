<?php

declare(strict_types=1);

namespace Spreadsheet\Enum;

enum FileDestination: string
{
    use BaseEnumTrait;

    case GOOGLE_SHEETS = 'google_sheets';
}
