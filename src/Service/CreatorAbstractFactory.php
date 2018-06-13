<?php

namespace T4web\DomainModule\Service;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use T4webDomain\Service\Creator;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Service\Creator
 *
 * @package T4web\DomainModule\Service
 */
class CreatorAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Service\Creator')) == 'Service\Creator';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Service\Creator', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $repository = $container->get("$moduleName\\$entityName\\Infrastructure\\Repository");
            $entityFactory = $container->get("$moduleName\\$entityName\\EntityFactory");
            $entityEventManager = $container->get("$moduleName\\$entityName\\EntityEventManager");
        } else {
            $entityName = $namespaceParts[0];
            $repository = $container->get("$entityName\\Infrastructure\\Repository");
            $entityFactory = $container->get("$entityName\\EntityFactory");
            $entityEventManager = $container->get("$entityName\\EntityEventManager");
        }

        return new Creator($repository, $entityFactory, $entityEventManager);
    }
}
