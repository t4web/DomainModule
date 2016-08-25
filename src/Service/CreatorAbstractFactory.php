<?php

namespace T4web\DomainModule\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webDomain\Service\Creator;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Service\Creator
 *
 * @package T4web\DomainModule\Service
 */
class CreatorAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Service\Creator')) == 'Service\Creator';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Service\Creator', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $repository = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Repository");
            $entityFactory = $serviceManager->get("$moduleName\\$entityName\\EntityFactory");
            $entityEventManager = $serviceManager->get("$moduleName\\$entityName\\EntityEventManager");
        } else {
            $entityName = $namespaceParts[0];
            $repository = $serviceManager->get("$entityName\\Infrastructure\\Repository");
            $entityFactory = $serviceManager->get("$entityName\\Infrastructure\\EntityFactory");
            $entityEventManager = $serviceManager->get("$entityName\\EntityEventManager");
        }

        return new Creator($repository, $entityFactory, $entityEventManager);
    }
}
