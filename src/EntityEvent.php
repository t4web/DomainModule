<?php

namespace T4web\DomainModule;

use Zend\EventManager\Event;
use T4webDomainInterface\EntityInterface;

class EntityEvent extends Event
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * EntityEvent constructor.
     * @param string $name
     * @param EntityInterface $entity
     * @param array $data
     */
    public function __construct($name, EntityInterface $entity, array $data = []) {
        $this->name = $name;
        $this->entity = $entity;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}