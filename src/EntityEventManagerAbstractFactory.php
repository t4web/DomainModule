<?php

namespace T4web\DomainModule;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManager as ZendEventManager;

class EntityEventManagerAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('EntityEventManager')) == 'EntityEventManager';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $eventManager = new ZendEventManager();
        $entityEventManager = new EntityEventManager($eventManager);

        $entityParams = explode('\\', $requestedName);
        if (isset($entityParams[0])) {
            $eventManager->setIdentifiers([$entityParams[0]]);
        }

        return $entityEventManager;
    }
}
