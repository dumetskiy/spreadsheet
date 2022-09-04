<?php

declare(strict_types=1);

namespace Spreadsheet\Validator\Configuration;

use Spreadsheet\Enum\DataType;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[\Attribute]
class IsAllowedDataTypeValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ApplicationConfiguration $applicationConfiguration
    ) {}

    /**
     * @param DataType $value
     * @param IsAllowedDataType $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$this->applicationConfiguration->isDataTypeAllowed($value)) {
            $this
                ->context
                ->buildViolation($constraint->dataTypeNotAllowedMessage)
                ->setParameter('{{ dataType }}', $value->value)
                ->addViolation();
        }
    }
}
