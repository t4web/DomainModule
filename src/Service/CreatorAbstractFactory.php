<?php

namespace T4web\DomainModule\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webDomain\Service\Creator;

/**
 * Create Service by template:
 *   MODULE-NAME\ENTITY-NAME\Service\Creator
 *
 * @package T4web\DomainModule\Service
 */
class CreatorAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('Service\Creator')) == 'Service\Creator';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $namespace = strstr($requestedName, 'Service\Creator', true);

        list($moduleName, $entityName) = explode('\\', $namespace);

        if ($serviceManager->has("$moduleName\\$entityName\\CreateValidator")) {
            $validator = $serviceManager->get("$moduleName\\$entityName\\CreateValidator");
        } else {
            $validator = $serviceManager->get("$moduleName\\$entityName\\Validator");
        }

        return new Creator(
            $validator,
            $serviceManager->get("$moduleName\\$entityName\\Infrastructure\\Repository"),
            $serviceManager->get("$moduleName\\$entityName\\EntityFactory")
        );
    }
}