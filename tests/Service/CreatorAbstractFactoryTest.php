<?php

namespace T4web\DomainModuleTest;

use T4web\DomainModule\Service\CreatorAbstractFactory;

class CreatorAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new CreatorAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $validatorMock = $this->getMock("T4webDomainInterface\\ValidatorInterface");

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo("$moduleName\\$entityName\\Validator"))
            ->will($this->returnValue($validatorMock));

        $repositoryMock = $this->getMock("T4webDomainInterface\\Infrastructure\\RepositoryInterface");

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo("$moduleName\\$entityName\\Infrastructure\\Repository"))
            ->will($this->returnValue($repositoryMock));

        $entityFactoryMock = $this->getMock("T4webDomainInterface\\EntityFactoryInterface");

        $this->serviceLocatorMock->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo("$moduleName\\$entityName\\EntityFactory"))
            ->will($this->returnValue($entityFactoryMock));

        $requestedName = "$moduleName\\$entityName\\Service\\Creator";

        $service = $this->abstractFactory->createServiceWithName(
            $this->serviceLocatorMock,
            $name = 'foo',
            $requestedName
        );

        $this->assertInstanceOf('T4webDomainInterface\Service\CreatorInterface', $service);
        $this->assertAttributeSame($validatorMock, 'validator', $service);
        $this->assertAttributeSame($repositoryMock, 'repository', $service);
        $this->assertAttributeSame($entityFactoryMock, 'entityFactory', $service);
    }

    public function testCanCreateServiceWithName() {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Service\\Creator"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Service\\Creator\\Foo"
            )
        );
    }
}
