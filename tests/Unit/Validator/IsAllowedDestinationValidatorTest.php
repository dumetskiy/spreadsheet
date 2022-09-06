<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Unit\Validator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spreadsheet\Enum\FileDestination;
use Spreadsheet\Validator\Configuration\IsAllowedDestination;
use Spreadsheet\Validator\Configuration\IsAllowedDestinationValidator;
use Spreadsheet\ValueObject\Configuration\ApplicationConfiguration;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class IsAllowedDestinationValidatorTest extends TestCase
{
    public function testDisallowedDataTypeValidation(): void
    {
        $testedDestination = FileDestination::GOOGLE_SHEETS;
        $validatedConstraint = new IsAllowedDestination();
        $applicationConfigurationMock = $this->createApplicationConfigurationMock($testedDestination, false);

        $executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $executionContextMock
            ->expects($this->once())
            ->method('buildViolation')
            ->with($validatedConstraint->destinationNotAllowedMessage);
        $testClass = new IsAllowedDestinationValidator($applicationConfigurationMock);
        $testClass->initialize($executionContextMock);

        $testClass->validate($testedDestination, $validatedConstraint);
    }

    public function testAllowedDataTypeValidation(): void
    {
        $testedDestination = FileDestination::GOOGLE_SHEETS;
        $validatedConstraint = new IsAllowedDestination();
        $applicationConfigurationMock = $this->createApplicationConfigurationMock($testedDestination, true);

        $executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $executionContextMock
            ->expects($this->never())
            ->method('buildViolation');
        $testClass = new IsAllowedDestinationValidator($applicationConfigurationMock);
        $testClass->initialize($executionContextMock);

        $testClass->validate($testedDestination, $validatedConstraint);
    }

    private function createApplicationConfigurationMock(FileDestination $testedDestination, bool $isDestinationAllowed): MockObject
    {
        $applicationConfigurationMock = $this
            ->createMock(ApplicationConfiguration::class);

        $applicationConfigurationMock
            ->expects($this->once())
            ->method('isDestinationAllowed')
            ->with($testedDestination)
            ->willReturn($isDestinationAllowed);

        return $applicationConfigurationMock;
    }
}
