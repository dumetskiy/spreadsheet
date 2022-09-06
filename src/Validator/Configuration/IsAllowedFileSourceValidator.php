<?php

declare(strict_types=1);

namespace Spreadsheet\Validator\Configuration;

use Spreadsheet\Enum\FileSource;
use Spreadsheet\Parser\FileSourceParser;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[\Attribute]
class IsAllowedFileSourceValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ApplicationConfiguration $applicationConfiguration
    ) {}

    /**
     * @param string $value
     * @param IsAllowedFileSource $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        $fileSourceType = FileSourceParser::getSourceTypeFromSource($value);

        if (!$fileSourceType instanceof FileSource) {
            $this
                ->context
                ->buildViolation($constraint->invalidSourceTypeMessage)
                ->setParameter('{{ source }}', $value)
                ->addViolation();

            return;
        }

        if (!$this->applicationConfiguration->isSourceAllowed($fileSourceType)) {
            $this
                ->context
                ->buildViolation($constraint->sourceNotAllowedMessage)
                ->setParameter('{{ sourceType }}', $fileSourceType->value)
                ->addViolation();
        }
    }
}
