<?php

namespace T4web\DomainModuleTest\Infrastructure;

use T4web\DomainModule\Infrastructure\QueryBuilderAbstractFactory;

class QueryBuilderAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new QueryBuilderAbstractFactory();
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

        $requestedName = "$moduleName\\$entityName\\Infrastructure\\QueryBuilder";

        $service = $this->abstractFactory->createServiceWithName(
            $this->serviceLocatorMock,
            $name = 'foo',
            $requestedName
        );

        $this->assertInstanceOf('T4webInfrastructure\QueryBuilder', $service);
        $this->assertAttributeSame($configMock, 'config', $service);
    }

    public function testCanCreateServiceWithName() {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\QueryBuilder"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\QueryBuilder\\Foo"
            )
        );
    }
}
