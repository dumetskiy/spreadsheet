<?php

declare(strict_types=1);

namespace Spreadsheet\Parser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\ValueObject\Spreadsheet;
use Spreadsheet\ValueObject\SpreadsheetFile;

interface DataParserInterface
{
    public function parseFileData(SpreadsheetFile $spreadsheetFile, DataType $dataType): Spreadsheet;
}
