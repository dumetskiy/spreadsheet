<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\Strategy;

use Psr\Log\LoggerInterface;
use Spreadsheet\Exception\Configuration\ConfigurationException;
use Spreadsheet\Factory\DataLoader\DataLoaderFactory;
use Spreadsheet\Factory\DataParser\DataParserFactory;
use Spreadsheet\Factory\Publisher\SpreadsheetPublisherFactory;
use Spreadsheet\Parser\FileSourceParser;
use Spreadsheet\Strategy\SpreadsheetUploadStrategy;
use Spreadsheet\ValueObject\Configuration\UploadOperationConfiguration;

class SpreadsheetUploadStrategyFactory
{
    public function __construct(
        private readonly DataLoaderFactory $dataLoaderFactory,
        private readonly DataParserFactory $dataParserFactory,
        private readonly SpreadsheetPublisherFactory $publisherFactory,
        private readonly LoggerInterface $logger,
    ) {}

    public function buildFromUploadOperationConfiguration(
        UploadOperationConfiguration $uploadOperationConfiguration
    ): SpreadsheetUploadStrategy {
        $fileSourceType = FileSourceParser::getSourceTypeFromSource($uploadOperationConfiguration->fileSource);

        if (null === $fileSourceType) {
            throw ConfigurationException::create(sprintf(
                'Source type for the provided file source "%s" can\'t be determined.',
                $uploadOperationConfiguration->fileSource
            ));
        }

        return new SpreadsheetUploadStrategy(
            $uploadOperationConfiguration,
            $this->dataLoaderFactory->getForSource($fileSourceType),
            $this->dataParserFactory->getForDataType($uploadOperationConfiguration->dataType),
            $this->publisherFactory->getForDestination($uploadOperationConfiguration->fileDestination),
            $this->logger,
        );
    }
}
