<?php

namespace T4web\DomainModule\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webDomain\Service\Deleter;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Service\Deleter
 *
 * @package T4web\DomainModule\Service
 */
class DeleterAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Service\Deleter')) == 'Service\Deleter';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Service\Deleter', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $repository = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Repository");
            $entityEventManager = $serviceManager->get("$moduleName\\$entityName\\EntityEventManager");
        } else {
            $entityName = $namespaceParts[0];
            $entityEventManager = $serviceManager->get("$entityName\\EntityEventManager");
        }

        return new Deleter($repository, $entityEventManager);
    }
}
