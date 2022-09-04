<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\Configuration;

use Spreadsheet\Enum\Command\Argument;
use Spreadsheet\Enum\Command\Option;
use Spreadsheet\Enum\DataType;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Exception\Command\CommandInputException;
use Spreadsheet\ValueObject\Configuration\UploadOperationConfiguration;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;

class UploadOperationConfigurationFactory
{
    public function createFromCommandInput(InputInterface $input): UploadOperationConfiguration
    {
        try {
            $fileDestinationHandle = $input->getOption(Option::DESTINATION->value);
            $fileDestination = FileDestination::tryFrom($fileDestinationHandle);

            if (null === $fileDestination) {
                throw CommandInputException::create(sprintf(
                    'Invalid value "%s" provided for file destination', $fileDestinationHandle
                ));
            }

            $dataTypeHandle = $input->getOption(Option::DATA_TYPE->value);
            $dataType = DataType::tryFrom($dataTypeHandle);

            if (null === $dataType) {
                throw CommandInputException::create(sprintf(
                    'Invalid value "%s" provided for file data type', $dataTypeHandle
                ));
            }

            return new UploadOperationConfiguration(
                $input->getArgument(Argument::SOURCE->value),
                $fileDestination,
                $dataType,
                $input->getOption(Option::TARGET_FILENAME->value),
            );
        } catch (InvalidArgumentException) {
            throw CommandInputException::create('Failed to process spreadsheet upload command input');
        }
    }
}
