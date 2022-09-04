<?php

declare(strict_types=1);

namespace Spreadsheet\Attribute;

use Spreadsheet\Enum\DataType;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class DataParser
{
    public function __construct(
        public readonly DataType $dataType
    ) {}
}
