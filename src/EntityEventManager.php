<?php

namespace T4web\DomainModule;

use Zend\EventManager\EventManager;
use T4webDomainInterface\EventManagerInterface;
use T4webDomainInterface\EventInterface;

class EntityEventManager implements EventManagerInterface
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * EntityEventManager constructor.
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function trigger(EventInterface $event)
    {
        $event = new EntityEvent($event->getName(), $event->getEntity(), $event->getData());

        $this->eventManager->trigger($event);
    }

    /**
     * @param $event
     * @param $listener
     */
    public function attach($event, $listener = null)
    {
        $this->eventManager->attach($event, $listener);
    }
}