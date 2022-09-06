<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\Google;

use Google\Service\Sheets\Spreadsheet as GoogleSpreadsheet;
use Google\Service\Sheets\SpreadsheetProperties as GoogleSpreadsheetProperties;
use Google\Service\Sheets\ValueRange as GoogleValueRange;
use Spreadsheet\ValueObject\Spreadsheet;

class GoogleSpreadsheetFactory
{
    public function createFromGenericSpreadsheet(Spreadsheet $spreadsheet): GoogleSpreadsheet
    {
        $googleSpreadsheetProperties = new GoogleSpreadsheetProperties();
        $googleSpreadsheetProperties->setTitle($spreadsheet->file->filename);

        $googleSpreadsheet = new GoogleSpreadsheet();
        $googleSpreadsheet->setProperties($googleSpreadsheetProperties);

        return $googleSpreadsheet;
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param int<1, max> $chunkSize
     *
     * @return GoogleValueRange[]
     */
    public function buildValueRanges(Spreadsheet $spreadsheet, int $chunkSize): array
    {
        $rowsData = array_map(fn (array $rowData) => array_values($rowData), $spreadsheet->data);
        $rowsChunks = array_chunk($rowsData, $chunkSize);

        return array_map(fn (array $rows) => new GoogleValueRange(['values' => $rows]), $rowsChunks);
    }
}
