<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Configuration;

use Spreadsheet\Exception\SpreadsheetException;

class ConfigurationException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Spreadsheet configuration error occurred';
}
