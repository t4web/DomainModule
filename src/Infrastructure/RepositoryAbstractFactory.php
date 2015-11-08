<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webInfrastructure\Repository;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\Repository
 *
 * @package T4web\DomainModule\Infrastructure
 */
class RepositoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\Repository')) == 'Infrastructure\Repository';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Infrastructure\Repository', true);

        list($moduleName, $entityName) = explode('\\', $namespace);

        $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
        $tableGateway = new TableGateway('tasks', $dbAdapter);

        $eventManager = $serviceManager->get('EventManager');
        $eventManager->addIdentifiers($requestedName);

        return new Repository(
            $entityName,
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\CriteriaFactory"),
            $tableGateway,
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Mapper"),
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\QueryBuilder"),
            $eventManager
        );
    }
}