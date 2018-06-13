<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use T4webInfrastructure\CriteriaFactory;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\CriteriaFactory
 *
 * @package T4web\DomainModule\Infrastructure
 */
class CriteriaFactoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\CriteriaFactory')) == 'Infrastructure\CriteriaFactory';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Infrastructure\CriteriaFactory', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            list($moduleName, $entityName) = $namespaceParts;
            $config = $container->get("$moduleName\\$entityName\\Infrastructure\\Config");
        } else {
            $entityName = $namespaceParts[0];
            $config = $container->get("$entityName\\Infrastructure\\Config");
        }

        return new CriteriaFactory($config);
    }
}
