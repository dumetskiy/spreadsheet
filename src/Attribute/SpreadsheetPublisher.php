<?php

declare(strict_types=1);

namespace Spreadsheet\Attribute;

use Spreadsheet\Enum\FileDestination;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class SpreadsheetPublisher
{
    public function __construct(
        public readonly FileDestination $destination
    ) {}
}
