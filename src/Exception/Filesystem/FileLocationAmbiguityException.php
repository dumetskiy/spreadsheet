<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Filesystem;

use Spreadsheet\Exception\SpreadsheetException;

class FileLocationAmbiguityException extends SpreadsheetException
{
    protected static string $defaultMessage = 'There is an ambiguity in the given file address criteria';
}
