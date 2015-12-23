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

        list($moduleName, $entityName) = explode('\\', $namespace);

        return new Updater(
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Repository"),
            $serviceManager->get("$moduleName\\$entityName\\EntityEventManager")
        );
    }
}