<?php

declare(strict_types=1);

namespace Spreadsheet\ValueObject\Configuration;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Enum\FileSource;
use Spreadsheet\Validator\Configuration\IsAllowedDataType;
use Spreadsheet\Validator\Configuration\IsAllowedDestination;
use Spreadsheet\Validator\Configuration\IsAllowedFileSource;
use Symfony\Component\Validator\Constraints as Assert;

class UploadOperationConfiguration
{
    public function __construct(
        #[Assert\NotBlank(message: 'File source should not be empty')]
        #[IsAllowedFileSource]
        public readonly string $fileSource,
        #[IsAllowedDestination]
        public readonly FileDestination $fileDestination,
        #[IsAllowedDataType]
        public readonly DataType $dataType,
        public readonly ?string $destinationFileName = null,
    ) {}
}
