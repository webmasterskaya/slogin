<?php

namespace Joomla\Component\SLolgin\Administrator\Model;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Component\SLolgin\Administrator\Table\UsersTable;

defined('_JEXEC') or die();

class UserModel extends BaseDatabaseModel
{
    /**
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return UsersTable|bool
     * @throws \Exception
     */
    public function getTable($name = 'Users', $prefix = 'Administrator', $options = []): UsersTable|bool
    {
        return parent::getTable($name, $prefix, $options);
    }
}
