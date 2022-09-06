<?php

declare(strict_types=1);

namespace Spreadsheet\Command;

use Psr\Log\LoggerInterface;
use Spreadsheet\Enum\Command\Argument;
use Spreadsheet\Enum\Command\Option;
use Spreadsheet\Enum\DataType;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Exception\SpreadsheetException;
use Spreadsheet\Factory\Configuration\UploadOperationConfigurationFactory;
use Spreadsheet\Processor\SpreadsheetUploadProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'spreadsheet:upload',
    description: 'Uploads the spreadsheet to the Google Sheets'
)]
class SpreadsheetUploadCommand extends Command
{
    public function __construct(
        private readonly SpreadsheetUploadProcessor $spreadsheetUploadProcessor,
        private readonly UploadOperationConfigurationFactory $commandConfigurationFactory,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(
                        name: Argument::SOURCE->value,
                        mode: InputArgument::REQUIRED,
                        description: 'File location path/DSN',
                    ),
                    new InputOption(
                        name: Option::DESTINATION->value,
                        mode: InputOption::VALUE_OPTIONAL,
                        description: 'File upload destination',
                        default: FileDestination::GOOGLE_SHEETS->value,
                        suggestedValues: FileDestination::allValues(),
                    ),
                    new InputOption(
                        name: Option::DATA_TYPE->value,
                        mode: InputOption::VALUE_OPTIONAL,
                        description: 'Source file content type',
                        default: DataType::XML->value,
                        suggestedValues: DataType::allValues(),
                    ),
                    new InputOption(
                        name: Option::TARGET_FILENAME->value,
                        mode: InputOption::VALUE_OPTIONAL,
                        description: 'The destination file name',
                    ),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->logger->notice('Starting spreadsheet upload command...');
            $this->logger->info('Processing command input...');
            $uploadOperationConfiguration = $this->commandConfigurationFactory->createFromCommandInput($input);
            $this->spreadsheetUploadProcessor->process($uploadOperationConfiguration);
            $this->logger->notice('Spreadsheet successfully uploaded.');

            return self::SUCCESS;
        } catch (SpreadsheetException $exception) {
            $this->logger->error($exception->getMessage());
        } catch (\Throwable) {
            $this->logger->error('Unhandled error occurred');
        }

        return self::FAILURE;
    }
}
