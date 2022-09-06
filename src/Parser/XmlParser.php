<?php

declare(strict_types=1);

namespace Spreadsheet\Parser;

use Spreadsheet\Attribute\DataParser;
use Spreadsheet\Enum\DataType;
use Spreadsheet\Exception\Parser\ParserException;
use Spreadsheet\ValueObject\Spreadsheet;
use Spreadsheet\ValueObject\SpreadsheetFile;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

#[DataParser(DataType::XML)]
class XmlParser implements DataParserInterface
{
    use SerializerAwareParserTrait;

    public function parseFileData(SpreadsheetFile $spreadsheetFile, DataType $dataType): Spreadsheet
    {
        $spreadsheetData = $this->decodeContents($spreadsheetFile->content, XmlEncoder::FORMAT);

        if (!is_array($spreadsheetData) || empty($spreadsheetData)) {
            throw ParserException::create('Source file content data structure is not applicable as a spreadsheet.');
        }

        if (1 < count($spreadsheetData)) {
            throw ParserException::create(sprintf(
                'Source document provided contains more than one root node type (%s)',
                implode(',', array_keys($spreadsheetData))
            ));
        }

        return new Spreadsheet($spreadsheetFile, current($spreadsheetData));
    }
}
