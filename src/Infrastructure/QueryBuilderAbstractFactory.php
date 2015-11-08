<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webInfrastructure\QueryBuilder;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\QueryBuilder
 *
 * @package T4web\DomainModule\Infrastructure
 */
class QueryBuilderAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\QueryBuilder')) == 'Infrastructure\QueryBuilder';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Infrastructure\QueryBuilder', true);

        list($moduleName, $entityName) = explode('\\', $namespace);

        return new QueryBuilder(
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Config")
        );
    }
}