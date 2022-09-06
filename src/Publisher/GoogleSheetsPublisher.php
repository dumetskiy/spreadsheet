<?php

declare(strict_types=1);

namespace Spreadsheet\Publisher;

use Google\Service\Sheets as GoogleSheetsService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Spreadsheet\Attribute\SpreadsheetPublisher;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Factory\Google\GoogleSpreadsheetFactory;
use Spreadsheet\ValueObject\Spreadsheet;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[SpreadsheetPublisher(FileDestination::GOOGLE_SHEETS)]
class GoogleSheetsPublisher implements SpreadsheetPublisherInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const DEFAULT_ROWS_APPEND_CONFIGURATION = [
        'valueInputOption' => 'USER_ENTERED',
    ];
    private const DEFAULT_ROWS_APPEND_RANGE = 'A:Z';

    /**
     * @param GoogleSheetsService $sheetsService
     * @param int<1, max> $sheetChunkSize
     * @param GoogleSpreadsheetFactory $googleSpreadsheetFactory
     */
    public function __construct(
        #[Autowire(service: 'spreadsheet.google.service.sheets')]
        private readonly GoogleSheetsService $sheetsService,
        #[Autowire('%spreadsheet.google.api.sheet.chunk_size%')]
        private readonly int $sheetChunkSize,
        private readonly GoogleSpreadsheetFactory $googleSpreadsheetFactory,
    ) {}

    public function publish(Spreadsheet $spreadsheet, FileDestination $destination): void
    {
        $this->logger->info('Creating Google spreadsheet...');
        $googleSpreadsheet = $this->googleSpreadsheetFactory->createFromGenericSpreadsheet($spreadsheet);
        $googleSpreadsheet = $this->sheetsService->spreadsheets->create($googleSpreadsheet);
        $spreadsheetId = $googleSpreadsheet->getSpreadsheetId();

        $this->logger->info(sprintf(
            'Empty spreadsheet %s successfully created.', $googleSpreadsheet->getSpreadsheetUrl()
        ));

        $this->logger->info('Building spreadsheet data...');
        $valueRanges = $this->googleSpreadsheetFactory->buildValueRanges($spreadsheet, $this->sheetChunkSize);
        $chunksCount = count($valueRanges);

        foreach ($valueRanges as $valueRangeIndex => $valueRange) {
            $this->logger->info(sprintf(
                'Appending chunk %d/%d to the spreadsheet...', $valueRangeIndex + 1, $chunksCount
            ));
            $this->sheetsService->spreadsheets_values->append(
                $spreadsheetId,
                self::DEFAULT_ROWS_APPEND_RANGE,
                $valueRange,
                self::DEFAULT_ROWS_APPEND_CONFIGURATION
            );
        }

        $this->logger->notice(sprintf(
            'Spreadsheet successfully configured at %s.', $googleSpreadsheet->getSpreadsheetUrl()
        ));
    }
}
