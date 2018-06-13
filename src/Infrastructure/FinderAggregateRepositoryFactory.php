<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\SequenceFeature;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use T4webInfrastructure\Config;
use T4webInfrastructure\FinderAggregateRepository;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\FinderAggregateRepository
 *
 * @package T4web\DomainModule\Infrastructure
 */
class FinderAggregateRepositoryFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\FinderAggregateRepository')) == 'Infrastructure\FinderAggregateRepository';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Infrastructure\FinderAggregateRepository', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            /** @var Config $config */
            $config = $container->get("$moduleName\\$entityName\\Infrastructure\\Config");
            $repository = $container->get("$moduleName\\$entityName\\Infrastructure\\Repository");
            $mapper = $container->get("$moduleName\\$entityName\\Infrastructure\\Mapper");
            $entityFactory = $container->get("$moduleName\\$entityName\\EntityFactory");
        } else {
            $entityName = $namespaceParts[0];
            /** @var Config $config */
            $config = $container->get("$entityName\\Infrastructure\\Config");
            $repository = $container->get("$entityName\\Infrastructure\\Repository");
            $mapper = $container->get("$entityName\\Infrastructure\\Mapper");
            $entityFactory = $container->get("$entityName\\EntityFactory");
        }

        $appConfig = $container->get('Config');

        if (!isset($appConfig['entity_map'])) {
            throw new ServiceNotCreatedException("You must define
                and configure Comments\\Comment in 'entity_map' config entry");
        }

        $relationsConfig = $appConfig['entity_map'][$entityName]['relations'];

        $relatedRepository = [];
        foreach ($relationsConfig as $relatedEntity => $joinRule) {
            $repositoryClass = $relatedEntity . '\Infrastructure\Repository';
            $relatedRepository[$relatedEntity] = $container->get($repositoryClass);
        }

        $features = [];
        $tableSequence = $config->getSequence($entityName);
        $tablePrimaryKey = $config->getPrimaryKey($entityName);
        if (!empty($tableSequence) && !empty($tablePrimaryKey)) {
            $features[] = new SequenceFeature($tablePrimaryKey, $tableSequence);
        }

        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');

        $tableGateway = new TableGateway($config->getTable($entityName), $dbAdapter, $features);

        return new FinderAggregateRepository(
            $tableGateway,
            $mapper,
            $entityFactory,
            $repository,
            $relatedRepository,
            $relationsConfig
        );
    }
}
