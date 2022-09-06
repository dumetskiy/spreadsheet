<?php

declare(strict_types=1);

namespace Spreadsheet\Strategy;

use Psr\Log\LoggerInterface;
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
        private readonly LoggerInterface $logger,
    ) {}

    public function execute(): void
    {
        $this->logger->info('Loading source file content...');
        $spreadsheetFile = $this->dataLoader->load($this->operationConfiguration->fileSource);

        $this->logger->info('Parsing source file content...');
        $spreadsheet = $this->dataParser->parseFileData($spreadsheetFile, $this->operationConfiguration->dataType);

        $this->logger->info('Publishing file...');
        $this->publisher->publish($spreadsheet, $this->operationConfiguration->fileDestination);
    }
}
