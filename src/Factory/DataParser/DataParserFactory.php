<?php

declare(strict_types=1);

namespace Spreadsheet\Factory\DataParser;

use Spreadsheet\Enum\DataType;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Parser\DataParserInterface;

class DataParserFactory
{
    /**
     * @var array<string, DataParserInterface> the data type - data parser map
     */
    private array $dataParsersMap = [];

    public function pushDataParser(DataType $dataType, DataParserInterface $dataParser): void
    {
        if (isset($this->dataParsersMap[$dataType->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to register more than one data parser for data type "%s" [%s | %s]',
                $dataType->value,
                $this->dataParsersMap[$dataType->value]::class,
                $dataParser::class,
            ));
        }

        $this->dataParsersMap[$dataType->value] = $dataParser;
    }

    public function getForDataType(DataType $dataType): DataParserInterface
    {
        if (!isset($this->dataParsersMap[$dataType->value])) {
            throw DependencyInjectionException::create(sprintf(
                'Attempted to fetch data parser for data type "%s" which is not configured',
                $dataType->value,
            ));
        }

        return $this->dataParsersMap[$dataType->value];
    }
}
