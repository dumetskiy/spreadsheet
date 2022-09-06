<?php

declare(strict_types=1);

namespace Spreadsheet\Validator\Configuration;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsAllowedDestination extends Constraint
{
    public string $destinationNotAllowedMessage = 'The given destination {{ destination }} is not allowed by the application configuration';
}
