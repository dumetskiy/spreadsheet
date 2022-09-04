<?php

declare(strict_types=1);

namespace Spreadsheet\Loader;

use Spreadsheet\ValueObject\SpreadsheetFile;

interface DataLoaderInterface
{
    public function load(string $fileSource): SpreadsheetFile;
}