<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\DataLoader;

use Spreadsheet\Enum\FileSource;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Loader\DataLoaderInterface;

class DataLoaderFactory
{
    /**
     * @var array<string, DataLoaderInterface> the file source - data loader map
     */
    private array $dataLoadersMap = [];

    public function pushDataLoader(FileSource $fileSource, DataLoaderInterface $dataLoader): void
    {
        if (isset($this->dataLoadersMap[$fileSource->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to register more than one data loader for file source "%s" [%s | %s]',
                $fileSource->value,
                $this->dataLoadersMap[$fileSource->value]::class,
                $dataLoader::class,
            ));
        }

        $this->dataLoadersMap[$fileSource->value] = $dataLoader;
    }

    public function getForSource(FileSource $fileSource): DataLoaderInterface
    {
        if (!isset($this->dataLoadersMap[$fileSource->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to fetch data loader for file source "%s" which is not configured',
                $fileSource->value,
            ));
        }

        return $this->dataLoadersMap[$fileSource->value];
    }
}
