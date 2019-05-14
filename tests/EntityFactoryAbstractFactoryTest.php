<?php

namespace T4web\DomainModuleTest;

use T4web\DomainModule\EntityFactoryAbstractFactory;

class EntityFactoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new EntityFactoryAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $requestedName = "$moduleName\\$entityName\\EntityFactory";

        $service = $this->abstractFactory->__invoke(
            $this->serviceLocatorMock,
            $requestedName
        );

        $this->assertInstanceOf('T4webDomain\EntityFactory', $service);
        $this->assertAttributeSame("$moduleName\\$entityName\\$entityName", 'entityClass', $service);
        $this->assertAttributeSame('ArrayObject', 'collectionClass', $service);
    }

    public function testCanCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreate(
                $this->serviceLocatorMock,
                "$moduleName\\$entityName\\EntityFactory"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreate(
                $this->serviceLocatorMock,
                "$moduleName\\$entityName\\EntityFactory\\Foo"
            )
        );
    }
}
