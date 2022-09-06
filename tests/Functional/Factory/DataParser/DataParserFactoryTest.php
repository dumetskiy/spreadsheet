<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Factory\DataParser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Factory\DataParser\DataParserFactory;
use Spreadsheet\Parser\JsonParser;
use Spreadsheet\Parser\XmlParser;
use Spreadsheet\Parser\YamlParser;
use Spreadsheet\Tests\Functional\ContainerAwareKernelTestCase;

class DataParserFactoryTest extends ContainerAwareKernelTestCase
{
    private DataParserFactory $dataParserFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataParserFactory = $this->container->get(DataParserFactory::class);
    }

    /**
     * @dataProvider getDataTypeDataParserClassMap
     */
    public function testGetForDataType(DataType $dataType, string $dataParserClass): void
    {
        $this->assertInstanceOf($dataParserClass, $this->dataParserFactory->getForDataType($dataType));
    }

    public function testPushDataParser(): void
    {
        $this->expectException(DependencyInjectionException::class);
        $this->dataParserFactory->pushDataParser(DataType::XML, new XmlParser());
    }

    private function getDataTypeDataParserClassMap(): array
    {
        return [
            [DataType::YAML, YamlParser::class],
            [DataType::XML, XmlParser::class],
            [DataType::JSON, JsonParser::class],
        ];
    }
}
