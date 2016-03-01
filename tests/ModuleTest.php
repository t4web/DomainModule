<?php

namespace T4web\DomainModuleTest;

use T4web\DomainModule\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $module = new Module();

        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $app = $this->prophesize('Zend\Mvc\Application');
        $sm = $this->prophesize('Zend\ServiceManager\ServiceManager');
        $mm = $this->prophesize('Zend\ModuleManager\ModuleManager');

        $event->getApplication()->willReturn($app->reveal());
        $app->getServiceManager()->willReturn($sm->reveal());
        $sm->get('ModuleManager')->willReturn($mm->reveal());
        $mm->getModule('T4webBase');

        $module->onBootstrap($event->reveal());

        $config = $module->getConfig();

        $this->assertArrayHasKey('service_manager', $config);
        $this->assertArrayHasKey('abstract_factories', $config['service_manager']);

        $autoloaderConfig = $module->getAutoloaderConfig();

        $this->assertArrayHasKey('Zend\Loader\StandardAutoloader', $autoloaderConfig);
        $this->assertArrayHasKey('namespaces', $autoloaderConfig['Zend\Loader\StandardAutoloader']);
        $this->assertArrayHasKey(
            'T4web\DomainModule',
            $autoloaderConfig['Zend\Loader\StandardAutoloader']['namespaces']
        );
    }
}
