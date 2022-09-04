<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Command;

use Spreadsheet\Exception\SpreadsheetException;

class CommandInputException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Command input exception occurred';
}
