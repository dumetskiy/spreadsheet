<?php

declare(strict_types=1);

namespace Spreadsheet\Attribute;

use Spreadsheet\Enum\FileSource;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class DataLoader
{
    public function __construct(
        public readonly FileSource $fileSource
    ) {}
}
