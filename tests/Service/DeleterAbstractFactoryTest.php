<?php

namespace T4web\DomainModuleTest\Service;

use T4web\DomainModule\Service\DeleterAbstractFactory;

class DeleterAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocatorMock;
    private $abstractFactory;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->abstractFactory = new DeleterAbstractFactory();
    }

    public function testCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $repositoryMock = $this->getMock("T4webDomainInterface\\Infrastructure\\RepositoryInterface");

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->with("$moduleName\\$entityName\\Infrastructure\\Repository")
            ->willReturn($repositoryMock);

        $requestedName = "$moduleName\\$entityName\\Service\\Deleter";

        $service = $this->abstractFactory->__invoke(
            $this->serviceLocatorMock,
            $requestedName
        );

        $this->assertInstanceOf('T4webDomainInterface\ServiceInterface', $service);
        $this->assertAttributeSame($repositoryMock, 'repository', $service);
    }

    public function testCanCreateServiceWithName()
    {
        $moduleName = 'Tasks';
        $entityName = 'Task';

        $this->assertTrue(
            $this->abstractFactory->canCreate(
                $this->serviceLocatorMock,
                "$moduleName\\$entityName\\Service\\Deleter"
            )
        );
        $this->assertFalse(
            $this->abstractFactory->canCreate(
                $this->serviceLocatorMock,
                "$moduleName\\$entityName\\Service\\Deleter\\Foo"
            )
        );
    }
}
