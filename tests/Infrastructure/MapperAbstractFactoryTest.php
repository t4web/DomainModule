<?php

namespace T4web\DomainModuleTest\Infrastructure;

use T4web\DomainModule\Infrastructure\MapperAbstractFactory;

class MapperAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new MapperAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $configMock = $this->getMockBuilder("T4webInfrastructure\\Config")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\Config")
            ->willReturn($configMock);

        $configMock->expects($this->once())
            ->method('getColumnsAsAttributesMap')
            ->with($entityName)
            ->willReturn([]);

        $entityFactoryMock = $this->getMockBuilder("T4webDomainInterface\\EntityFactoryInterface")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with("$moduleName\\$entityName\\EntityFactory")
            ->willReturn($entityFactoryMock);

        $requestedName = "$moduleName\\$entityName\\Infrastructure\\Mapper";

        $service = $this->abstractFactory->createServiceWithName(
            $this->serviceLocatorMock,
            $name = 'foo',
            $requestedName
        );

        $this->assertInstanceOf('T4webInfrastructure\Mapper', $service);
        $this->assertAttributeSame([], 'columnsAsAttributesMap', $service);
        $this->assertAttributeSame($entityFactoryMock, 'entityFactory', $service);
    }

    public function testCanCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\Mapper"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\Mapper\\Foo"
            )
        );
    }
}
