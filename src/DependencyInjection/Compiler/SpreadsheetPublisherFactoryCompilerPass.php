<?php

declare(strict_types=1);

namespace Spreadsheet\DependencyInjection\Compiler;

use Spreadsheet\Attribute\SpreadsheetPublisher;
use Spreadsheet\Exception\DependencyInjection\DependencyInjectionException;
use Spreadsheet\Factory\Publisher\SpreadsheetPublisherFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class SpreadsheetPublisherFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $publisherFactoryDefinition = $container->getDefinition(SpreadsheetPublisherFactory::class);

        if (!$publisherFactoryDefinition instanceof Definition) {
            throw DependencyInjectionException::create(sprintf(
                'Failed to configure spreadsheet publisher as "%s" is not configured as a service',
                SpreadsheetPublisherFactory::class
            ));
        }

        foreach ($container->findTaggedServiceIds('spreadsheet.publisher') as $publisherId => $tags) {
            $publisherDefinition = $container->getDefinition($publisherId);
            /** @phpstan-ignore-next-line */
            $publisherClassReflection = new \ReflectionClass($publisherDefinition->getClass());

            foreach ($publisherClassReflection->getAttributes() as $attributeReflection) {
                $attribute = $attributeReflection->newInstance();

                if (!$attribute instanceof SpreadsheetPublisher) {
                    continue;
                }

                $publisherFactoryDefinition->addMethodCall('pushPublisher', [
                    $attribute->destination,
                    new Reference($publisherId, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE),
                ]);
            }
        }
    }
}
