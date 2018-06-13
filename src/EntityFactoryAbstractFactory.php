<?php

namespace T4web\DomainModule;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use T4webDomain\EntityFactory;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\EntityFactory
 *
 * @package T4web\DomainModule
 */
class EntityFactoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('EntityFactory')) == 'EntityFactory';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'EntityFactory', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        $collectionClass = 'ArrayObject';
        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $entityClass = "$moduleName\\$entityName\\$entityName";
        } else {
            $entityName = $namespaceParts[0];

            /** @var Config $config */
            $config = $container->get("$entityName\\Infrastructure\\Config");
            $entityClass = $config->getEntityClass($entityName);
            $collectionClass = $config->getCollectionClass($entityName);
        }

        return new EntityFactory($entityClass, $collectionClass);
    }
}
