<?php

namespace T4web\DomainModule;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager as ZendEventManager;

class EntityEventManagerAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        return substr($requestedName, -strlen('EntityEventManager')) == 'EntityEventManager';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName)
    {
        $eventManager = new ZendEventManager();
        $entityEventManager = new EntityEventManager($eventManager);

        $entityParams = explode('\\', $requestedName);
        if (isset($entityParams[0])) {
            $eventManager->setIdentifiers($entityParams[0]);
        }

        return $entityEventManager;
    }
}
