<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\Publisher;

use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Publisher\SpreadsheetPublisherInterface;

class SpreadsheetPublisherFactory
{
    /**
     * @var array<string, SpreadsheetPublisherInterface> the spreadsheet destination - publisher map
     */
    private array $spreadsheetPublishersMap = [];

    public function pushPublisher(FileDestination $destination, SpreadsheetPublisherInterface $spreadsheetPublisher): void
    {
        if (isset($this->spreadsheetPublishersMap[$destination->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to register more than one publisher for destination "%s" [%s | %s]',
                $destination->value,
                $this->spreadsheetPublishersMap[$destination->value]::class,
                $spreadsheetPublisher::class,
            ));
        }

        $this->spreadsheetPublishersMap[$destination->value] = $spreadsheetPublisher;
    }

    public function getForDestination(FileDestination $destination): SpreadsheetPublisherInterface
    {
        if (!isset($this->spreadsheetPublishersMap[$destination->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to fetch spreadsheet publisher for destination "%s" which is not configured',
                $destination->value,
            ));
        }

        return $this->spreadsheetPublishersMap[$destination->value];
    }
}
