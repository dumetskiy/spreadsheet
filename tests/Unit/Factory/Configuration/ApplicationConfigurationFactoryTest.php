<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Unit\Factory\Configuration;

use Monolog\Test\TestCase;
use Spreadsheet\Enum\DataType;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Enum\FileSource;
use Spreadsheet\Exception\Configuration\ConfigurationException;
use Spreadsheet\Factory\Configuration\ApplicationConfigurationFactory;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;

class ApplicationConfigurationFactoryTest extends TestCase
{
    /**
     * @dataProvider getValidApplicationConfigurations
     */
    public function testValidConfigurations(
        array $allowedSourceHandles,
        array $allowedDestinationHandles,
        array $allowedDataTypeHandles,
    ): void {
        $this->assertInstanceOf(
            ApplicationConfiguration::class,
            $this->createTestInstance($allowedSourceHandles, $allowedDestinationHandles, $allowedDataTypeHandles)(),
        );
    }

    /**
     * @dataProvider getInvalidApplicationConfigurations
     */
    public function testInvalidConfigurations(
        array $allowedSourceHandles,
        array $allowedDestinationHandles,
        array $allowedDataTypeHandles,
    ): void {
        $this->expectException(ConfigurationException::class);
        $this->createTestInstance($allowedSourceHandles, $allowedDestinationHandles, $allowedDataTypeHandles)();
    }

    private function createTestInstance(
        array $allowedSourceHandles,
        array $allowedDestinationHandles,
        array $allowedDataTypeHandles,
    ): ApplicationConfigurationFactory {
        return new ApplicationConfigurationFactory($allowedSourceHandles, $allowedDestinationHandles, $allowedDataTypeHandles);
    }

    private function getValidApplicationConfigurations(): array
    {
        return [
            [[FileSource::FTP->value, FileSource::HTTP->value], [FileDestination::GOOGLE_SHEETS->value], [DataType::XML->value]],
            [[FileSource::FTP->value, FileSource::HTTP->value, FileSource::FILE->value], [], []],
            [[FileSource::FTP->value, FileSource::HTTP->value, FileSource::FILE->value], [], [DataType::JSON->value, DataType::YAML->value]],
            [[], [], []],
        ];
    }

    private function getInvalidApplicationConfigurations(): array
    {
        return [
            [[FileSource::FTP->value, 'some_string'], [FileDestination::GOOGLE_SHEETS], [DataType::XML->value]],
            [[null, FileSource::HTTP->value, FileSource::FILE->value], [], [new \ArrayObject()]],
            [[FileSource::FTP->value, 'text_value', FileSource::FILE->value], [], [0]],
            [[''], [null], [[]]],
        ];
    }
}
