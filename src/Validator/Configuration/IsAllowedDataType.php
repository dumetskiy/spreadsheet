<?php

declare(strict_types=1);

namespace Spreadsheet\Validator\Configuration;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsAllowedDataType extends Constraint
{
    public string $dataTypeNotAllowedMessage = 'The given data type {{ dataType }} is not allowed by the application configuration';
}
