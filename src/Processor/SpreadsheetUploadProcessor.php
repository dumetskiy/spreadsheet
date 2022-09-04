<?php

declare(strict_types=1);

namespace Spreadsheet\Processor;

use Spreadsheet\Exception\Validation\ValidationException;
use Spreadsheet\Factory\Strategy\SpreadsheetUploadStrategyFactory;
use Spreadsheet\ValueObject\Configuration\UploadOperationConfiguration;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SpreadsheetUploadProcessor
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SpreadsheetUploadStrategyFactory $uploadStrategyFactory
    ) {}

    public function process(UploadOperationConfiguration $uploadOperationConfiguration): void
    {
        // Validating the upload operation configuration provided
        $this->validateUploadOperationConfiguration($uploadOperationConfiguration);

        // Creating upload strategy based on the given configuration and executing it
        $this->uploadStrategyFactory
            ->buildFromUploadOperationConfiguration($uploadOperationConfiguration)
            ->execute();
    }

    private function validateUploadOperationConfiguration(UploadOperationConfiguration $uploadOperationConfiguration): void
    {
        $constraintViolationsList = $this->validator->validate($uploadOperationConfiguration);

        if ($constraintViolationsList->count()) {
            $errorMessages = array_map(fn (ConstraintViolationInterface $constraintViolation)
                => $constraintViolation->getMessage(), iterator_to_array($constraintViolationsList));

            throw ValidationException::create(sprintf(
                'Constraint violations detected when validating the upload operation configuration: %s',
                implode(', ', $errorMessages),
            ));
        }
    }
}
