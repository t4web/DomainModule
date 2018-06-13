<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use T4webInfrastructure\Mapper;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\Mapper
 *
 * @package T4web\DomainModule\Infrastructure
 */
class MapperAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\Mapper')) == 'Infrastructure\Mapper';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Infrastructure\Mapper', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            /** @var Config $config */
            $config = $container->get("$moduleName\\$entityName\\Infrastructure\\Config");
        } else {
            $entityName = $namespaceParts[0];
            /** @var Config $config */
            $config = $container->get("$entityName\\Infrastructure\\Config");
        }

        return new Mapper(
            $config->getColumnsAsAttributesMap($entityName),
            $config->getSerializedColumns($entityName)
        );
    }
}
