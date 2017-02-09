<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webInfrastructure\InMemoryRepository;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\InMemoryRepository
 *
 * @package T4web\DomainModule\Infrastructure
 */
class InMemoryRepositoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\InMemoryRepository')) == 'Infrastructure\InMemoryRepository';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Infrastructure\InMemoryRepository', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            /** @var Config $config */
            $config = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Config");
            $criteriaFactory = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\CriteriaFactory");
            $entityFactory = $serviceManager->get("$moduleName\\$entityName\\EntityFactory");
        } else {
            $entityName = $namespaceParts[0];
            /** @var Config $config */
            $config = $serviceManager->get("$entityName\\Infrastructure\\Config");
            $criteriaFactory = $serviceManager->get("$entityName\\Infrastructure\\CriteriaFactory");
            $entityFactory = $serviceManager->get("$entityName\\EntityFactory");
        }

        $eventManager = $serviceManager->get('EventManager');
        $eventManager->addIdentifiers("$entityName\\Infrastructure\\Repository");
        $collectionClass = $config->getCollectionClass($entityName);

        return new InMemoryRepository(
            $entityName,
            $collectionClass,
            $criteriaFactory,
            $entityFactory,
            $eventManager
        );
    }
}
