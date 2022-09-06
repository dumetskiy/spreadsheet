<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Parser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Parser\JsonParser;

class JsonParserTest extends AbstractParserTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = $this->container->get(JsonParser::class);
    }

    protected function getValidContentToDataMap(): array
    {
        return [
            ['{"key1": "value1", "key2": 100}', ['key1' => 'value1', 'key2' => 100]],
            ['{"key1": ["value1", "value2", "value3"]}', ['key1' => ['value1',  'value2', 'value3']]],
        ];
    }

    protected function getInvalidContents(): array
    {
        return [
            [''],
            ['{}'],
            ['text_value'],
            ['[test_key]'],
            ['["test": value]'],
            ['{test_key: test_value}'],
        ];
    }

    protected function getDataType(): DataType
    {
        return DataType::JSON;
    }
}
