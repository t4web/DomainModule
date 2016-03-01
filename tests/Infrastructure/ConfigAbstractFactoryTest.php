<?php

namespace T4web\DomainModuleTest\Infrastructure;

use T4web\DomainModule\Infrastructure\ConfigAbstractFactory;

class ConfigAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new ConfigAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';
        $someConfig = [
            'entity_map' => [
                'Task' => [/*...*/]
            ]
        ];

        $moduleManagerMock = $this->getMockBuilder('Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->with("ModuleManager")
            ->willReturn($moduleManagerMock);

        $moduleMock = $this->getMock('Zend\ModuleManager\Feature\ConfigProviderInterface');

        $moduleManagerMock->expects($this->once())
            ->method('getModule')
            ->with($moduleName)
            ->willReturn($moduleMock);

        $moduleMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($someConfig);

        $requestedName = "$moduleName\\$entityName\\Infrastructure\\Config";

        $service = $this->abstractFactory->createServiceWithName(
            $this->serviceLocatorMock,
            $name = 'foo',
            $requestedName
        );

        $this->assertInstanceOf('T4webInfrastructure\Config', $service);
        $this->assertAttributeSame(['Task' => []], 'entityMap', $service);
    }

    public function testCanCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\Config"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreateServiceWithName(
                $this->serviceLocatorMock,
                'foo',
                "$moduleName\\$entityName\\Infrastructure\\Config\\Foo"
            )
        );
    }
}
