<?php

declare(strict_types=1);

namespace Spreadsheet\Enum\Command;

enum Option: string
{
    case DESTINATION = 'destination';
    case DATA_TYPE = 'data-type';
    case TARGET_FILENAME = 'target-filename';
}
