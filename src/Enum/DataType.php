<?php

declare(strict_types=1);

namespace Spreadsheet\Enum;

enum DataType: string
{
    use BaseEnumTrait;

    case XML = 'xml';
    case CSV = 'csv';
    case JSON = 'json';
    case YAML = 'yaml';
}
