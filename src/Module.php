<?php
namespace T4web\DomainModule;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManager;
use \RuntimeException;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, BootstrapListenerInterface
{
    public function onBootstrap(EventInterface $e)
    {
        /** @var ModuleManager $moduleManager */
        $moduleManager = $e->getApplication()
            ->getServiceManager()
            ->get('ModuleManager');

        $baseModule = $moduleManager->getModule('T4webBase');

        if (!is_null($baseModule)) {
            throw new RuntimeException('T4web\DomainModule has conflict with T4webBase module, for use it you must disable T4webBase module.');
        }

    }

    public function getConfig($env = null)
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => dirname(__DIR__) . '/src',
                ),
            ),
        );
    }
}
