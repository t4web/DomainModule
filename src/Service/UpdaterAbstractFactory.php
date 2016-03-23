<?php

namespace T4web\DomainModule\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webDomain\Service\Updater;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Service\Creator
 *
 * @package T4web\DomainModule\Service
 */
class UpdaterAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Service\Updater')) == 'Service\Updater';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Service\Updater', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $repository = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Repository");
            $entityEventManager = $serviceManager->get("$moduleName\\$entityName\\EntityEventManager");
        } else {
            $entityName = $namespaceParts[0];
            $entityEventManager = $serviceManager->get("$entityName\\EntityEventManager");
        }

        return new Updater($repository, $entityEventManager);
    }
}
