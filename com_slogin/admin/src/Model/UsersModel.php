<?php

namespace Joomla\Component\SLolgin\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die();


class UsersModel extends ListModel
{
    protected function populateState($ordering = null, $direction = null)
    {
        $app = Factory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = $app->getInput()->getString('layout', 'default'))
        {
            $this->context .= '.' . $layout;
        }

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $provider = $this->getUserStateFromRequest($this->context . '.filter.provider', 'filter_provider', 0, 'string');
        $this->setState('filter.provider', $provider);


        // List state information.
        parent::populateState('su.id', 'desc');
    }

    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $query->select('su.*, u.username, u.name')
            ->from('#__slogin_users as su')
            ->leftJoin('#__users as u ON u.id = su.user_id')
            ->group('su.id')
            ->order('su.id DESC');

        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('(su.provider LIKE ' . $search . ' OR su.user_id LIKE ' . $search . ' OR u.username LIKE ' . $search . ' OR u.name LIKE ' . $search . ')');
        }

        $provider = $this->getState('filter.provider');
        if (!empty($provider))
        {
            $query->where('su.provider = ' . $db->quote($provider));
        }

        return $query;
    }


    public function getProviders()
    {
        $db = $this->getDatabase();

        return $db->setQuery(
            $db->getQuery(true)
                ->select('DISTINCT `provider` AS value, `provider` AS text')
                ->from('#__slogin_users')
                ->order('value ASC')
        )->loadObjectList();
    }
}
