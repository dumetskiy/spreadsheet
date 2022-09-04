<?php

declare(strict_types=1);

namespace Spreadsheet\Strategy;

use Spreadsheet\Loader\DataLoaderInterface;
use Spreadsheet\Parser\DataParserInterface;
use Spreadsheet\Publisher\SpreadsheetPublisherInterface;
use Spreadsheet\ValueObject\Configuration\UploadOperationConfiguration;

final class SpreadsheetUploadStrategy
{
    public function __construct(
        private readonly UploadOperationConfiguration $operationConfiguration,
        private readonly DataLoaderInterface $dataLoader,
        private readonly DataParserInterface $dataParser,
        private readonly SpreadsheetPublisherInterface $publisher,
    ) {}

    public function execute(): void
    {
        // Loading source file content
        $spreadsheetFile = $this->dataLoader->load($this->operationConfiguration->fileSource);

        // Parsing contents
        $spreadsheet = $this->dataParser->parseFileData($spreadsheetFile, $this->operationConfiguration->dataType);

        // Publishing spreadsheet data
        $this->publisher->publish($spreadsheet, $this->operationConfiguration->fileDestination);
    }
}