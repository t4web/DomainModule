<?php

namespace T4web\DomainModule;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webDomain\EntityFactory;

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

        list($moduleName, $entityName) = explode('\\', $namespace);

        return new EntityFactory("$moduleName\\$entityName\\$entityName");
    }
}