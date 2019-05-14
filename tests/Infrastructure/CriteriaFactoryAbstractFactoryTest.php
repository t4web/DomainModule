<?php

namespace T4web\DomainModuleTest;

use T4web\DomainModule\Infrastructure\CriteriaFactoryAbstractFactory;
use T4webInfrastructure\CriteriaFactory;
use T4webInfrastructure\Config;

class CriteriaFactoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';
        $this->serviceLocatorMock = $this->prophesize('Zend\ServiceManager\ServiceLocatorInterface');
        $configMock = $this->prophesize(Config::class);

        $this->serviceLocatorMock->get("$moduleName\\$entityName\\Infrastructure\\Config")
            ->willReturn($configMock->reveal());

        $this->abstractFactory = new CriteriaFactoryAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $requestedName = "$moduleName\\$entityName\\Infrastructure\\CriteriaFactory";

        $service = $this->abstractFactory->__invoke(
            $this->serviceLocatorMock->reveal(),
            $requestedName
        );

        $this->assertInstanceOf(CriteriaFactory::class, $service);
    }

    public function testCanCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreate(
                $this->serviceLocatorMock->reveal(),
                "$moduleName\\$entityName\\Infrastructure\\CriteriaFactory"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreate(
                $this->serviceLocatorMock->reveal(),
                "$moduleName\\$entityName\\EntityFactory\\Foo"
            )
        );
    }
}
