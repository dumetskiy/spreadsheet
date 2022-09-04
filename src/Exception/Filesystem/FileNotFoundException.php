<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Filesystem;

use Spreadsheet\Exception\SpreadsheetException;

class FileNotFoundException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Failed to locate file';
}
