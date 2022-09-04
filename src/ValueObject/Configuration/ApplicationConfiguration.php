<?php

declare(strict_types=1);

namespace Spreadsheet\ValueObject\Configuration;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Enum\FileSource;

class ApplicationConfiguration
{
    /**
     * @param FileSource[] $allowedSources
     * @param FileDestination[] $allowedDestinations
     * @param DataType[] $allowedDataTypes
     */
    public function __construct(
        private readonly array $allowedSources,
        private readonly array $allowedDestinations,
        private readonly array $allowedDataTypes,
    ) {}

    public function isSourceAllowed(FileSource $fileSource): bool
    {
        return in_array($fileSource, $this->allowedSources);
    }

    public function isDestinationAllowed(FileDestination $fileDestination): bool
    {
        return in_array($fileDestination, $this->allowedDestinations);
    }

    public function isDataTypeAllowed(DataType $dataType): bool
    {
        return in_array($dataType, $this->allowedDataTypes);
    }
}
