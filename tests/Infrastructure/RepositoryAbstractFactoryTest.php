<?php

namespace T4web\DomainModuleTest\Infrastructure;

use T4web\DomainModule\Infrastructure\RepositoryAbstractFactory;

class RepositoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new RepositoryAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $dbAdapterMock = $this->getMockBuilder("Zend\\Db\\Adapter\\Adapter")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->with("Zend\\Db\\Adapter\\Adapter")
            ->willReturn($dbAdapterMock);

        $configMock = $this->getMockBuilder("T4webInfrastructure\\Config")
            ->disableOriginalConstructor()
            ->getMock();

        $configMock->expects($this->once())
            ->method('getTable')
            ->with($entityName)
            ->willReturn('some_table');

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\Config")
            ->willReturn($configMock);

        $emMock = $this->getMock("Zend\\EventManager\\EventManagerInterface");

        $this->serviceLocatorMock->expects($this->at(2))
            ->method('get')
            ->with("EventManager")
            ->willReturn($emMock);

        $criteriaFactoryMock = $this->getMockBuilder("T4webInfrastructure\\CriteriaFactory")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(3))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\CriteriaFactory")
            ->willReturn($criteriaFactoryMock);

        $mapperMock = $this->getMockBuilder("T4webInfrastructure\\Mapper")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(4))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\Mapper")
            ->willReturn($mapperMock);

        $requestedName = "$moduleName\\$entityName\\Infrastructure\\Repository";

        $emMock->expects($this->once())
            ->method('addIdentifiers')
            ->with($requestedName);

        $service = $this->abstractFactory->createServiceWithName(
            $this->serviceLocatorMock,
            $name = 'foo',
            $requestedName
        );

        $this->assertInstanceOf('T4webDomainInterface\Infrastructure\RepositoryInterface', $service);
        $this->assertAttributeSame($entityName, 'entityName', $service);
        $this->assertAttributeSame($criteriaFactoryMock, 'criteriaFactory', $service);
        $this->assertAttributeSame($mapperMock, 'mapper', $service);
        $this->assertAttributeSame($emMock, 'eventManager', $service);
    }

    public function testCanCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\Repository"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\Repository\\Foo"
            )
        );
    }
}
