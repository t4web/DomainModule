<?php

namespace T4web\DomainModuleTest;

use T4web\DomainModule\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $module = new Module();

        $config = $module->getConfig();

        $this->assertArrayHasKey('service_manager', $config);
        $this->assertArrayHasKey('abstract_factories', $config['service_manager']);

        $autoloaderConfig = $module->getAutoloaderConfig();

        $this->assertArrayHasKey('Zend\Loader\StandardAutoloader', $autoloaderConfig);
        $this->assertArrayHasKey('namespaces', $autoloaderConfig['Zend\Loader\StandardAutoloader']);
        $this->assertArrayHasKey('T4web\DomainModule', $autoloaderConfig['Zend\Loader\StandardAutoloader']['namespaces']);
    }
}
