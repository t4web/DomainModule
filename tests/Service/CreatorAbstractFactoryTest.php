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

        $eventManagerMock = $this->getMock("T4webDomainInterface\\EventManagerInterface");
        $repositoryMock = $this->getMock("T4webDomainInterface\\Infrastructure\\RepositoryInterface");

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\Repository")
            ->willReturn($repositoryMock);

        $entityFactoryMock = $this->getMock("T4webDomainInterface\\EntityFactoryInterface");

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with("$moduleName\\$entityName\\EntityFactory")
            ->willReturn($entityFactoryMock);

        $this->serviceLocatorMock->expects($this->at(2))
            ->method('get')
            ->with("$moduleName\\$entityName\\EntityEventManager")
            ->willReturn($eventManagerMock);

        $requestedName = "$moduleName\\$entityName\\Service\\Creator";

        $service = $this->abstractFactory->createServiceWithName(
            $this->serviceLocatorMock,
            $name = 'foo',
            $requestedName
        );

        $this->assertInstanceOf('T4webDomainInterface\Service\CreatorInterface', $service);
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
