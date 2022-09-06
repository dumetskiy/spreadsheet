<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Parser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Parser\XmlParser;
use Spreadsheet\Parser\YamlParser;

class YamlParserTest extends AbstractParserTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = $this->container->get(YamlParser::class);
    }

    protected function getValidContentToDataMap(): array
    {
        return [
            [
                'key1:
                    key2: value1   
                ',
                ['key1' => ['key2' => 'value1']],
                ['125:', [125 => null]],
            ],
        ];
    }

    protected function getInvalidContents(): array
    {
        return [
            ['a: b: 6'],
            ['test'],
            [''],
        ];
    }

    protected function getDataType(): DataType
    {
        return DataType::YAML;
    }
}
