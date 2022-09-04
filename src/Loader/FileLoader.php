<?php

declare(strict_types=1);

namespace Spreadsheet\Loader;

use Spreadsheet\Attribute\DataLoader;
use Spreadsheet\Enum\FileSource;
use Spreadsheet\Exception\Filesystem\FileAccessException;
use Spreadsheet\Exception\Filesystem\FileLocationAmbiguityException;
use Spreadsheet\Exception\Filesystem\FileNotFoundException;
use Spreadsheet\ValueObject\SpreadsheetFile;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

#[
    DataLoader(FileSource::FILE),
    DataLoader(FileSource::DATA),
    DataLoader(FileSource::FTP),
    DataLoader(FileSource::HTTP),
    DataLoader(FileSource::HTTPS),
    DataLoader(FileSource::PHP),
    DataLoader(FileSource::SSH2),
]
class FileLoader implements DataLoaderInterface
{
    private Finder $finder;

    public function __construct() {
        $this->finder = new Finder();
    }

    public function load(string $fileSource): SpreadsheetFile
    {
        $fileName = pathinfo($fileSource, PATHINFO_BASENAME);
        $fileLocationPath = pathinfo($fileSource, PATHINFO_DIRNAME);

        $files = $this->finder->in($fileLocationPath)->name($fileName)->files();
        $resultsCount = $files->count();

        if (0 === $resultsCount) {
            throw FileNotFoundException::create(sprintf(
                'Failed to locate the file with the specified address (%s)',
                $fileSource
            ));
        }

        if (1 < $resultsCount) {
            throw FileLocationAmbiguityException::create(sprintf(
                'More then one file located with given address criteria (%s)',
                $fileSource
            ));
        }

        /** @var SplFileInfo $file */
        $file = current(iterator_to_array($files));

        if (!$file->isReadable()) {
            throw FileAccessException::create(sprintf(
                'Failed to read the provided file (%s)',
                $fileSource
            ));
        }

        return new SpreadsheetFile($fileName, $fileLocationPath, $file->getContents());
    }
}
