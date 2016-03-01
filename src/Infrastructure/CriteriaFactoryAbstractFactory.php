<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webInfrastructure\CriteriaFactory;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\CriteriaFactory
 *
 * @package T4web\DomainModule\Infrastructure
 */
class CriteriaFactoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\CriteriaFactory')) == 'Infrastructure\CriteriaFactory';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Infrastructure\CriteriaFactory', true);

        list($moduleName, $entityName) = explode('\\', $namespace);

        return new CriteriaFactory(
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Config")
        );
    }
}
