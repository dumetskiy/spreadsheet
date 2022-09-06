<?php

declare(strict_types=1);

namespace Spreadsheet\Parser;

use Spreadsheet\Attribute\DataParser;
use Spreadsheet\Enum\DataType;
use Spreadsheet\Exception\Parser\ParserException;
use Spreadsheet\ValueObject\Spreadsheet;
use Spreadsheet\ValueObject\SpreadsheetFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[DataParser(DataType::JSON)]
class JsonParser implements DataParserInterface
{
    use SerializerAwareParserTrait;

    public function parseFileData(SpreadsheetFile $spreadsheetFile, DataType $dataType): Spreadsheet
    {
        $spreadsheetData = $this->decodeContents($spreadsheetFile->content, JsonEncoder::FORMAT);

        if (!is_array($spreadsheetData) || empty($spreadsheetData)) {
            throw ParserException::create('Source file content data structure is not applicable as a spreadsheet.');
        }

        return new Spreadsheet($spreadsheetFile, $spreadsheetData);
    }
}
