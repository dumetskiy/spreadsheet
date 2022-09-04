<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Parser;

use Spreadsheet\Exception\SpreadsheetException;

class ParserException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Failed to parse file contents';
}
