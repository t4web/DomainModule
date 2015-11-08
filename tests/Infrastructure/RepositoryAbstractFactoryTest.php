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

        $emMock = $this->getMock("Zend\\EventManager\\EventManagerInterface");

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with("EventManager")
            ->willReturn($emMock);

        $criteriaFactoryMock = $this->getMock("T4webInfrastructure\\CriteriaFactory");

        $this->serviceLocatorMock->expects($this->at(2))
            ->method('get')
            ->with("T4webInfrastructure\\CriteriaFactory")
            ->willReturn($criteriaFactoryMock);

        $mapperMock = $this->getMockBuilder("T4webInfrastructure\\Mapper")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(3))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\Mapper")
            ->willReturn($mapperMock);

        $qbMock = $this->getMockBuilder("T4webInfrastructure\\QueryBuilder")
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(4))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\QueryBuilder")
            ->willReturn($qbMock);

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
        $this->assertAttributeSame($qbMock, 'queryBuilder', $service);
        $this->assertAttributeSame($emMock, 'eventManager', $service);
    }

    public function testCanCreateServiceWithName() {
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