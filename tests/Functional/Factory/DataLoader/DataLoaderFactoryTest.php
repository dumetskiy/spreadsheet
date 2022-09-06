<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional\Factory\DataLoader;

use Spreadsheet\Enum\FileSource;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Factory\DataLoader\DataLoaderFactory;
use Spreadsheet\Loader\FileLoader;
use Spreadsheet\Tests\Functional\ContainerAwareKernelTestCase;

class DataLoaderFactoryTest extends ContainerAwareKernelTestCase
{
    private DataLoaderFactory $dataLoaderFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataLoaderFactory = $this->container->get(DataLoaderFactory::class);
    }

    /**
     * @dataProvider getFileSourceDataLoaderClassMap
     */
    public function testGetForSource(FileSource $fileSource, string $dataLoaderClass): void
    {
        $this->assertInstanceOf($dataLoaderClass, $this->dataLoaderFactory->getForSource($fileSource));
    }

    public function testPushDataLoader(): void
    {
        $this->expectException(DependencyInjectionException::class);
        $this->dataLoaderFactory->pushDataLoader(FileSource::HTTP, new FileLoader());
    }

    private function getFileSourceDataLoaderClassMap(): array
    {
        return [
            [FileSource::SSH2, FileLoader::class],
            [FileSource::DATA, FileLoader::class],
            [FileSource::FTP, FileLoader::class],
            [FileSource::HTTP, FileLoader::class],
            [FileSource::HTTPS, FileLoader::class],
            [FileSource::PHP, FileLoader::class],
        ];
    }
}
