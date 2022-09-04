<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\DependencyInjection;

use Spreadsheet\Exception\SpreadsheetException;

class DependencyInjectionException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Failed to build the container with given dependencies configuration';
}
