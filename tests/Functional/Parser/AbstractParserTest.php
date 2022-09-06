<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Parser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Exception\Parser\ParserException;
use Spreadsheet\Parser\DataParserInterface;
use Spreadsheet\Tests\Functional\ContainerAwareKernelTestCase;
use Spreadsheet\ValueObject\Spreadsheet;
use Spreadsheet\ValueObject\SpreadsheetFile;

abstract class AbstractParserTest extends ContainerAwareKernelTestCase
{
    protected DataParserInterface $parser;

    /**
     * @dataProvider getValidContentToDataMap
     */
    public function testParseValidFileData(string $fileContent, array $expectedData): void
    {
        $spreadsheetFile = new SpreadsheetFile('', '', $fileContent);
        $spreadsheet = new Spreadsheet($spreadsheetFile, $expectedData);

        $this->assertEquals(
            $spreadsheet,
            $this->parser->parseFileData($spreadsheetFile, $this->getDataType()),
        );
    }

    /**
     * @dataProvider getInvalidContents
     */
    public function testParseInvalidFileData(string $fileContent): void
    {
        $spreadsheetFile = new SpreadsheetFile('', '', $fileContent);
        $this->expectException(ParserException::class);
        $this->parser->parseFileData($spreadsheetFile, $this->getDataType());
    }

    abstract protected function getValidContentToDataMap(): array;

    abstract protected function getInvalidContents(): array;

    abstract protected function getDataType(): DataType;
}
