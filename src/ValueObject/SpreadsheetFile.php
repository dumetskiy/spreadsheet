<?php

declare(strict_types=1);

namespace Spreadsheet\ValueObject;

class SpreadsheetFile
{
    public function __construct(
        public readonly string $filename,
        public readonly string $path,
        public readonly string $content,
    ) {}
}
