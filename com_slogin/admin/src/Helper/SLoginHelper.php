<?php
/**
 * SLogin
 *
 * @version       2.9.1
 * @author        SmokerMan, Arkadiy, Joomline
 * @copyright     © 2012-2020. All rights reserved.
 * @license       GNU/GPL v.3 or later.
 */

namespace Joomla\Component\SLogin\Administrator\Helper;

// No direct access.
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;

defined('_JEXEC') or die();

/**
 * SLogin helper.
 *
 * @package        Joomla.Administrator
 * @subpackage     com_slogin
 */
class SLoginHelper
{
    /**
     * @return CMSObject
     * @throws \Exception
     */
    public static function getActions(): CMSObject
    {
        $app       = Factory::getApplication();
        $user      = $app->getIdentity();
        $result    = new CMSObject();
        $assetName = 'com_slogin';
        $actions   = ['core.admin', 'core.manage'];
        foreach ($actions as $action)
        {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
}
