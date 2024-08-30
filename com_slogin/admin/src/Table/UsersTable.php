<?php

namespace Joomla\Component\SLolgin\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\ParameterType;

defined('_JEXEC') or die();

class UsersTable extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     */
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__slogin_users', 'id', $db);
    }

    function deleteUserRows($user_id): bool
    {
        try
        {
            $db = $this->getDbo();
            $db->setQuery($db->getQuery(true)
                ->delete($db->quoteName('#__slogin_users'))
                ->where('user_id = ' . (int) $user_id)
                ->bind(':user_id', $user_id, ParameterType::INTEGER)
            )->execute();
        }
        catch (\Exception $e)
        {
            $this->setError($e->getMessage());

            return false;
        }

        return true;
    }
}
