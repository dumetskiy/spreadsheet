<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Loader;

use Spreadsheet\Exception\Filesystem\FileLocationAmbiguityException;
use Spreadsheet\Exception\Filesystem\FileNotFoundException;
use Spreadsheet\Loader\FileLoader;
use Spreadsheet\Tests\Functional\ContainerAwareKernelTestCase;
use Spreadsheet\ValueObject\SpreadsheetFile;

class FileLoaderTest extends ContainerAwareKernelTestCase
{
    private const TESTS_ROOT_DIR = __DIR__ . '/../..';

    private FileLoader $fileLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileLoader = $this->container->get(FileLoader::class);
    }

    /**
     * @dataProvider getValidFilePaths
     */
    public function testValidFilePathsLoading(string $filePath): void
    {
        $this->assertInstanceOf(SpreadsheetFile::class, $this->fileLoader->load($filePath));
    }

    public function testInvalidPathLoading(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->fileLoader->load('/some/file/path.txt');

        $this->expectException(FileLocationAmbiguityException::class);
        $this->fileLoader->load(sprintf('%s/data/test_*.xml', self::TESTS_ROOT_DIR));
    }

    private function getValidFilePaths(): array
    {
        return [
            [sprintf('file://%s/data/test.json', self::TESTS_ROOT_DIR)],
            [sprintf('%s/data/test.xml', self::TESTS_ROOT_DIR)],
            [sprintf('%s/data/test.xml', self::TESTS_ROOT_DIR)],
        ];
    }
}
