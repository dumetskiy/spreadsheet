<?php

declare(strict_types=1);

namespace Spreadsheet\Publisher;

use Spreadsheet\Attribute\SpreadsheetPublisher;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\ValueObject\Spreadsheet;

#[SpreadsheetPublisher(FileDestination::GOOGLE_SHEETS)]
class GoogleSheetsPublisher implements SpreadsheetPublisherInterface
{
    public function publish(Spreadsheet $spreadsheet, FileDestination $destination): void
    {
        var_dump($spreadsheet->data);
    }
}
