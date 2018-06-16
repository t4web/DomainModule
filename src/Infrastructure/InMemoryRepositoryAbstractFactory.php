<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
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
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\InMemoryRepository')) == 'Infrastructure\InMemoryRepository';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Infrastructure\InMemoryRepository', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            /** @var Config $config */
            $config = $container->get("$moduleName\\$entityName\\Infrastructure\\Config");
            $criteriaFactory = $container->get("$moduleName\\$entityName\\Infrastructure\\CriteriaFactory");
            $entityFactory = $container->get("$moduleName\\$entityName\\EntityFactory");
        } else {
            $entityName = $namespaceParts[0];
            /** @var Config $config */
            $config = $container->get("$entityName\\Infrastructure\\Config");
            $criteriaFactory = $container->get("$entityName\\Infrastructure\\CriteriaFactory");
            $entityFactory = $container->get("$entityName\\EntityFactory");
        }

        $eventManager = $container->get('EventManager');
        $eventManager->addIdentifiers(["$entityName\\Infrastructure\\Repository"]);
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
