<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareKernelTestCase extends KernelTestCase
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->container = static::getContainer();
    }
}
