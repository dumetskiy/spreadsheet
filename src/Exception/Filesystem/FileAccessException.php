<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Filesystem;

use Spreadsheet\Exception\SpreadsheetException;

class FileAccessException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Failed to access the specified file';
}
