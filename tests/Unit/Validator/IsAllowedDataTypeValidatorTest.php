<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Unit\Validator;

use Google\Service\Analytics\Resource\Data;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spreadsheet\Enum\DataType;
use Spreadsheet\Validator\Configuration\IsAllowedDataType;
use Spreadsheet\Validator\Configuration\IsAllowedDataTypeValidator;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class IsAllowedDataTypeValidatorTest extends TestCase
{
    public function testDisallowedDataTypeValidation(): void
    {
        $testedDataType = DataType::YAML;
        $validatedConstraint = new IsAllowedDataType();
        $applicationConfigurationMock = $this->createApplicationConfigurationMock($testedDataType, false);

        $executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $executionContextMock
            ->expects($this->once())
            ->method('buildViolation')
            ->with($validatedConstraint->dataTypeNotAllowedMessage);
        $testClass = new IsAllowedDataTypeValidator($applicationConfigurationMock);
        $testClass->initialize($executionContextMock);

        $testClass->validate($testedDataType, $validatedConstraint);
    }

    public function testAllowedDataTypeValidation(): void
    {
        $testedDataType = DataType::JSON;
        $validatedConstraint = new IsAllowedDataType();
        $applicationConfigurationMock = $this->createApplicationConfigurationMock($testedDataType, true);

        $executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $executionContextMock
            ->expects($this->never())
            ->method('buildViolation');
        $testClass = new IsAllowedDataTypeValidator($applicationConfigurationMock);
        $testClass->initialize($executionContextMock);

        $testClass->validate($testedDataType, $validatedConstraint);
    }

    private function createApplicationConfigurationMock(DataType $testedDataType, bool $isDataTypeAllowed): MockObject
    {
        $applicationConfigurationMock = $this
            ->createMock(ApplicationConfiguration::class);

        $applicationConfigurationMock
            ->expects($this->once())
            ->method('isDataTypeAllowed')
            ->with($testedDataType)
            ->willReturn($isDataTypeAllowed);

        return $applicationConfigurationMock;
    }
}