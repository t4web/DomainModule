<?php

namespace T4web\DomainModule;

use Zend\EventManager\Event;
use T4webDomainInterface\EntityInterface;
use T4webDomainInterface\EventInterface;

class EntityEvent extends Event implements EventInterface
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
     * @var array
     */
    protected $validData = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * EntityEvent constructor.
     * @param string $name
     * @param EntityInterface $entity
     * @param array $data
     */
    public function __construct($name, EntityInterface $entity = null, array $data = []) {
        $this->name = $name;
        $this->entity = $entity;
        $this->data = $data;
        $this->validData = $data;
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

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getValidData()
    {
        if (!empty($this->errors)) {
            return [];
        }
        return $this->validData;
    }

    /**
     * @param array $validData
     */
    public function setValidData($validData)
    {
        $this->validData = $validData;
    }
}