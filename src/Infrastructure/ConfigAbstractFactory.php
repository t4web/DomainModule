<?php

namespace T4web\DomainModule\Infrastructure;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Infrastructure\Config
 *
 * @package T4web\DomainModule\Infrastructure
 */
class ConfigAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\Config')) == 'Infrastructure\Config';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Infrastructure\Config', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        if (count($namespaceParts) > 1) {
            $entityName = $namespaceParts[1];
        } else {
            $entityName = $namespaceParts[0];
        }

        $config = $serviceManager->get('Config');

        if (!isset($config['entity_map'])) {
            throw new ServiceNotCreatedException("You must define
                and configure $entityName in 'entity_map' config entry");
        }

        return new Config($config['entity_map']);
    }
}
