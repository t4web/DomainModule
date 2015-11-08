<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webInfrastructure\Mapper;

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

        list($moduleName, $entityName) = explode('\\', $namespace);

        return new Mapper(
            [],
            $serviceManager->get("$moduleName\\$entityName\\EntityFactory")
        );
    }
}