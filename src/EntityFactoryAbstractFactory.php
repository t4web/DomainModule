<?php

namespace T4web\DomainModule;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webDomain\EntityFactory;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\EntityFactory
 *
 * @package T4web\DomainModule
 */
class EntityFactoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('EntityFactory')) == 'EntityFactory';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'EntityFactory', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $entityClass = "$moduleName\\$entityName\\$entityName";
        } else {
            $entityName = $namespaceParts[0];

            /** @var Config $config */
            $config = $serviceManager->get("$entityName\\Infrastructure\\Config");
            $entityClass = $config->getEntityClass($entityName);
        }

        return new EntityFactory($entityClass);
    }
}
