<?php

declare(strict_types=1);

namespace Spreadsheet\Publisher;

use Spreadsheet\Enum\FileDestination;
use Spreadsheet\ValueObject\Spreadsheet;

interface SpreadsheetPublisherInterface
{
    public function publish(Spreadsheet $spreadsheet, FileDestination $destination): void;
}
