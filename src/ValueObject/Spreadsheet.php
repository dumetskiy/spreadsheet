<?php

declare(strict_types=1);

namespace Spreadsheet\ValueObject;

class Spreadsheet
{
    public function __construct(
        public readonly SpreadsheetFile $file,
        public readonly array $data,
    ) {}
}
