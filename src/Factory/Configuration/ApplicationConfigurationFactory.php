<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\Configuration;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Enum\FileSource;
use Spreadsheet\Exception\Configuration\ConfigurationException;
use Spreadsheet\Exception\Enum\EnumException;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ApplicationConfigurationFactory
{
    /**
     * @param string[] $allowedSourceHandles
     * @param string[] $allowedDestinationHandles
     * @param string[] $allowedDataTypeHandles
     */
    public function __construct(
        #[Autowire('%spreadsheet.configuration.allowed_sources%')]
        private readonly array $allowedSourceHandles,
        #[Autowire('%spreadsheet.configuration.allowed_destinations%')]
        private readonly array $allowedDestinationHandles,
        #[Autowire('%spreadsheet.configuration.allowed_data_types%')]
        private readonly array $allowedDataTypeHandles,
    ) {}

    public function __invoke(): ApplicationConfiguration
    {
        try {
            $allowedSources = FileSource::fromValues($this->allowedSourceHandles);
        } catch (EnumException) {
            throw ConfigurationException::create(sprintf(
                'Configured allowed file source does not exist, available options: %s',
                implode(',', FileSource::allValues()),
            ));
        }

        try {
            $allowedDestinations = FileDestination::fromValues($this->allowedDestinationHandles);
        } catch (EnumException) {
            throw ConfigurationException::create(sprintf(
                'Configured allowed file destination does not exist, available options: %s',
                implode(',', FileDestination::allValues()),
            ));
        }

        try {
            $allowedDataTypes = DataType::fromValues($this->allowedDataTypeHandles);
        } catch (EnumException) {
            throw ConfigurationException::create(sprintf(
                'Configured allowed source data type does not exist, available options: %s',
                implode(',', DataType::allValues()),
            ));
        }

        return new ApplicationConfiguration($allowedSources, $allowedDestinations, $allowedDataTypes);
    }
}
