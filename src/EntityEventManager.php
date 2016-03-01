<?php

namespace T4web\DomainModule;

use Zend\EventManager\EventManager;
use T4webDomainInterface\EventManagerInterface;
use T4webDomainInterface\EventInterface;
use T4webDomainInterface\EntityInterface;

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

    /**
     * @param EventInterface $event
     */
    public function trigger(EventInterface $event)
    {
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

    /**
     * @param $event
     * @param EntityInterface|null $entity
     * @param array $data
     * @return EntityEvent
     */
    public function createEvent($event, EntityInterface $entity = null, array $data = [])
    {
        return new EntityEvent($event, $entity, $data);
    }
}
