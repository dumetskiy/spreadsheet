<?php

declare(strict_types=1);

namespace Spreadsheet\DependencyInjection\Compiler;

use Spreadsheet\Attribute\DataParser;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Factory\DataLoader\DataLoaderFactory;
use Spreadsheet\Factory\DataParser\DataParserFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DataParserFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $dataParserFactoryDefinition = $container->getDefinition(DataParserFactory::class);

        if (!$dataParserFactoryDefinition instanceof Definition) {
            throw DependencyInjectionException::create(sprintf(
                'Failed to configure data parser as "%s" is not configured as a service',
                DataParserFactory::class
            ));
        }

        foreach ($container->findTaggedServiceIds('spreadsheet.data_parser') as $dataParserId => $tags) {
            $dataParserDefinition = $container->getDefinition($dataParserId);
            /** @phpstan-ignore-next-line */
            $dataParserClassReflection = new \ReflectionClass($dataParserDefinition->getClass());

            foreach ($dataParserClassReflection->getAttributes() as $attributeReflection) {
                $attribute = $attributeReflection->newInstance();

                if (!$attribute instanceof DataParser) {
                    continue;
                }

                $dataParserFactoryDefinition->addMethodCall('pushDataParser', [
                    $attribute->dataType,
                    new Reference($dataParserId, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE),
                ]);
            }
        }
    }
}
