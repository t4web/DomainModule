<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
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
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\Mapper')) == 'Infrastructure\Mapper';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Infrastructure\Mapper', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            /** @var Config $config */
            $config = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Config");
        } else {
            $entityName = $namespaceParts[0];
            /** @var Config $config */
            $config = $serviceManager->get("$entityName\\Infrastructure\\Config");
        }

        return new Mapper(
            $config->getColumnsAsAttributesMap($entityName),
            $config->getSerializedColumns($entityName)
        );
    }
}
