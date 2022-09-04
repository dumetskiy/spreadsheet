<?php

declare(strict_types=1);

namespace Spreadsheet;

use Spreadsheet\DependencyInjection\Compiler\DataLoaderFactoryCompilerPass;
use Spreadsheet\DependencyInjection\Compiler\DataParserFactoryCompilerPass;
use Spreadsheet\DependencyInjection\Compiler\SpreadsheetPublisherFactoryCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DataLoaderFactoryCompilerPass());
        $container->addCompilerPass(new DataParserFactoryCompilerPass());
        $container->addCompilerPass(new SpreadsheetPublisherFactoryCompilerPass());
    }
}
