<?php

declare(strict_types=1);

namespace Spreadsheet\Parser;

use Spreadsheet\Enum\FileSource;

class FileSourceParser
{
    private const SOURCE_DSN_REGEXP = '/^(?<source_type>[A-Za-z]*):\/\/.+$/';

    public static function getSourceTypeFromSource(string $sourcePath): ?FileSource
    {
        preg_match(self::SOURCE_DSN_REGEXP, $sourcePath, $matches);

        if (empty($matches)) {
            // Falling back to the local file source type if no protocol is present in the DSN/path
            return FileSource::FILE;
        }

        return FileSource::tryFrom($matches['source_type']);
    }
}
