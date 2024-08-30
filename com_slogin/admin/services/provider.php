<?php

use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\SLogin\Administrator\Extension\Component;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

\defined('JPATH_PLATFORM') or die;

return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory("\\Joomla\\Component\\SLogin"));
        $container->registerServiceProvider(new ComponentDispatcherFactory("\\Joomla\\Component\\SLogin"));
        $container->registerServiceProvider(new RouterFactory("\\Joomla\\Component\\SLogin"));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new Component($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setRegistry($container->get(Registry::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};
