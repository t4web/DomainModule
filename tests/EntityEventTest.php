<?php

namespace T4web\DomainModuleTest;

use T4web\DomainModule\EntityEvent;

class EntityEventTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $event = new EntityEvent('User');

        $data = ['key' => 'value'];
        $event->setData($data);

        $this->assertEquals($data, $event->getData());

        $errors = ['field' => 'error'];
        $event->setErrors($errors);

        $this->assertEquals($errors, $event->getErrors());

        $validData = ['validKey' => 'validValue'];
        $event->setValidData($validData);

        $this->assertEquals([], $event->getValidData());

        $event->setErrors([]);
        $this->assertEquals($validData, $event->getValidData());
    }
}
