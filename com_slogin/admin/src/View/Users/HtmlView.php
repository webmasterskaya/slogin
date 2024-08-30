<?php

namespace Joomla\Component\SLogin\Administrator\View\Users;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\SLogin\Administrator\Helper\SLoginHelper;

defined('_JEXEC') or die();

class HtmlView extends \Joomla\CMS\MVC\View\HtmlView
{

    function display($tpl = null)
    {
        // Assign data to the view
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');
        $this->providers  = $this->get('Providers');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode('<br />', $errors), 500);
        }

        // Set the toolbar
        $this->addToolBar();

        // Set title
        $this->document->setTitle(Text::_('COM_SLOGIN'));

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        $canDo   = SLoginHelper::getActions();

        ToolbarHelper::title(Text::_('COM_SLOGIN_USERS'), 'users');

        if ($canDo->get('core.admin'))
        {
            ToolbarHelper::deleteList(Text::_('COM_SLOGIN_CONFIRM'), 'remove_slogin_users', 'COM_SLOGIN_DELETE_USERS');
            ToolbarHelper::deleteList(Text::_('COM_SLOGIN_CONFIRM'), 'remove_joomla_users', 'COM_SLOGIN_DELETE_J_USERS');
        }
    }
}
