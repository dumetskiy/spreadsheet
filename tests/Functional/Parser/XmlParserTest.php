<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Parser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Parser\XmlParser;

class XmlParserTest extends AbstractParserTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = $this->container->get(XmlParser::class);
    }

    protected function getValidContentToDataMap(): array
    {
        return [
            [
                '<?xml version="1.0"?><catalog><book id="bk101"><author>Gambardella, Matthew</author></book></catalog>',
                ['@id' => 'bk101', 'author' => 'Gambardella, Matthew'],
            ],
        ];
    }

    protected function getInvalidContents(): array
    {
        return [
            [''],
            ['<?xml version="1.0"?><catalog><book id="bk101"><author></author>'],
            ['<author<Gambardella, Matthew</author>'],
        ];
    }

    protected function getDataType(): DataType
    {
        return DataType::XML;
    }
}
