<?php

declare(strict_types=1);

namespace Spreadsheet\Validator\Configuration;

use Spreadsheet\Enum\FileDestination;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[\Attribute]
class IsAllowedDestinationValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ApplicationConfiguration $applicationConfiguration
    ) {}

    /**
     * @param FileDestination $value
     * @param IsAllowedDestination $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$this->applicationConfiguration->isDestinationAllowed($value)) {
            $this
                ->context
                ->buildViolation($constraint->destinationNotAllowedMessage)
                ->setParameter('{{ destination }}', $value->value)
                ->addViolation();
        }
    }
}
