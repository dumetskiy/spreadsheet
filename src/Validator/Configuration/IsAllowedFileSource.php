<?php

declare(strict_types=1);

namespace Spreadsheet\Validator\Configuration;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsAllowedFileSource extends Constraint
{
    public string $invalidSourceTypeMessage = 'The given file source {{ source }} type can not be evaluated';
    public string $sourceNotAllowedMessage = 'The file source type {{ sourceType }} is not allowed by the application configuration';
}
