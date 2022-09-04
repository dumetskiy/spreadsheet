<?php

declare(strict_types=1);

namespace Spreadsheet\DependencyInjection\Compiler;

use Spreadsheet\Attribute\DataLoader;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Factory\DataLoader\DataLoaderFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DataLoaderFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $dataLoaderFactoryDefinition = $container->getDefinition(DataLoaderFactory::class);

        if (!$dataLoaderFactoryDefinition instanceof Definition) {
            throw DependencyInjectionException::create(sprintf(
                'Failed to configure data loaders as "%s" is not configured as a service',
                DataLoaderFactory::class
            ));
        }

        foreach ($container->findTaggedServiceIds('spreadsheet.data_loader') as $dataLoaderId => $tags) {
            $dataLoaderDefinition = $container->getDefinition($dataLoaderId);
            /** @phpstan-ignore-next-line */
            $dataLoaderClassReflection = new \ReflectionClass($dataLoaderDefinition->getClass());

            foreach ($dataLoaderClassReflection->getAttributes() as $attributeReflection) {
                $attribute = $attributeReflection->newInstance();

                if (!$attribute instanceof DataLoader) {
                    continue;
                }

                $dataLoaderFactoryDefinition->addMethodCall('pushDataLoader', [
                    $attribute->fileSource,
                    new Reference($dataLoaderId, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE),
                ]);
            }
        }
    }
}
