<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\SequenceFeature;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webInfrastructure\Repository;
use T4webInfrastructure\Config;

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

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            /** @var Config $config */
            $config = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Config");
            $criteriaFactory = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\CriteriaFactory");
            $mapper = $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Mapper");
            $entityFactory = $serviceManager->get("$moduleName\\$entityName\\EntityFactory");
        } else {
            $entityName = $namespaceParts[0];
            /** @var Config $config */
            $config = $serviceManager->get("$entityName\\Infrastructure\\Config");
            $criteriaFactory = $serviceManager->get("$entityName\\Infrastructure\\CriteriaFactory");
            $mapper = $serviceManager->get("$entityName\\Infrastructure\\Mapper");
            $entityFactory = $serviceManager->get("$entityName\\EntityFactory");
        }

        $features = [];
        $tableSequence = $config->getSequence($entityName);
        $tablePrimaryKey = $config->getPrimaryKey($entityName);
        if (!empty($tableSequence) && !empty($tablePrimaryKey)) {
            $features[] = new SequenceFeature($tablePrimaryKey, $tableSequence);
        }

        $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
        $tableGateway = new TableGateway($config->getTable($entityName), $dbAdapter, $features);

        $eventManager = $serviceManager->get('EventManager');
        $eventManager->addIdentifiers($requestedName);

        return new Repository(
            $entityName,
            $criteriaFactory,
            $tableGateway,
            $mapper,
            $entityFactory,
            $eventManager,
            $tablePrimaryKey
        );
    }
}
