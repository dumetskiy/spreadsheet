<?php

declare(strict_types=1);

namespace Spreadsheet\Exception\Validation;

use Spreadsheet\Exception\SpreadsheetException;

class ValidationException extends SpreadsheetException
{
    protected static string $defaultMessage = 'Constraint validation exception occurred';
}
