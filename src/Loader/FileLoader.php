<?php

declare(strict_types=1);

namespace Spreadsheet\Loader;

use Spreadsheet\Attribute\DataLoader;
use Spreadsheet\Enum\FileSource;
use Spreadsheet\Exception\Filesystem\FileAccessException;
use Spreadsheet\Exception\Filesystem\FileLocationAmbiguityException;
use Spreadsheet\Exception\Filesystem\FileNotFoundException;
use Spreadsheet\ValueObject\SpreadsheetFile;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
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
        $fileName = pathinfo($fileSource, \PATHINFO_BASENAME);
        $fileLocationPath = pathinfo($fileSource, \PATHINFO_DIRNAME);

        // Making sure there is a trailing slash at the end of the directory path
        if (DIRECTORY_SEPARATOR !== substr($fileLocationPath, -1)) {
            $fileLocationPath .= DIRECTORY_SEPARATOR;
        }

        try {
            $files = $this->finder->files()->in($fileLocationPath)->name($fileName);
            $resultsCount = $files->count();
        } catch (DirectoryNotFoundException) {
            $resultsCount = 0;
        }

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
