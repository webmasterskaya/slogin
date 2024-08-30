<?php

namespace Joomla\Component\SLogin\Administrator\Extension;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Plugin\PluginHelper;
use Psr\Container\ContainerInterface;

\defined('JPATH_PLATFORM') or die;

class Component extends MVCComponent implements BootableExtensionInterface, RouterServiceInterface
{
    use HTMLRegistryAwareTrait;
    use RouterServiceTrait;

    public function boot(ContainerInterface $container): void
    {
        PluginHelper::importPlugin('slogin');
    }
}
