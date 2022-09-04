<?php

declare(strict_types=1);

namespace Spreadsheet\Parser;

use Spreadsheet\Attribute\DataParser;
use Spreadsheet\Enum\DataType;
use Spreadsheet\ValueObject\Spreadsheet;
use Spreadsheet\ValueObject\SpreadsheetFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[DataParser(DataType::JSON)]
class JsonParser implements DataParserInterface
{
    use SerializerAwareParserTrait;

    public function parseFileData(SpreadsheetFile $spreadsheetFile, DataType $dataType): Spreadsheet
    {
        return new Spreadsheet(
            $spreadsheetFile,
            $this->decodeContents($spreadsheetFile->content, JsonEncoder::FORMAT),
        );
    }
}