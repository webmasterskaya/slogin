<?php
/**
 * SLogin
 *
 * @version       2.9.1
 * @author        SmokerMan, Arkadiy, Joomline
 * @copyright     © 2012-2020. All rights reserved.
 * @license       GNU/GPL v.3 or later.
 */

namespace Joomla\Component\SLogin\Administrator\Controller;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\User;
use Joomla\Component\SLogin\Administrator\Event\DeleteUserEvent;
use Joomla\Component\SLolgin\Administrator\Model\UserModel;
use Joomla\Component\SLolgin\Administrator\Table\UsersTable;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Input\Input;

defined('_JEXEC') or die();

/**
 * SLogin Main Controller
 *
 * @package        Joomla.Administrator
 * @subpackage     com_slogin
 */
class DisplayController extends BaseController
{

    use DatabaseAwareTrait;

    /**
     * The default view.
     *
     * @var  string
     *
     * @since  1.0.0
     */
    protected $default_view = 'settings';

    public function __construct($config = [], MVCFactoryInterface $factory = null, ?CMSApplication $app = null, ?Input $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        $this->setDatabase(Factory::getContainer()->get(DatabaseInterface::class));
    }

    public function clean(): void
    {
        $this->checkToken();

        try
        {
            $query = 'TRUNCATE TABLE  `#__slogin_users`';

            $this->getDatabase()
                ->setQuery($query)
                ->execute();

            $this->app->enqueueMessage(Text::_('COM_SLOGIN_USERS_TABLE_CLEARED'), 'message');
        }
        catch (\Exception $e)
        {
            $this->app->enqueueMessage($e->getMessage(), 'error');
        }

        $this->app->redirect('index.php?option=com_slogin&view=settings');
    }

    public function repair(): void
    {
        try
        {
            $db = $this->getDatabase();

            $uids = $db->setQuery(
                $db->getQuery(true)
                    ->select('s.user_id')
                    ->from($db->quoteName('#__slogin_users', 's'))
                    ->leftJoin($db->quoteName('#__users', 'u') . ' ON u.id = s.user_id')
                    ->where('u.id IS NULL')
            )->loadColumn();
        }
        catch (\Exception $e)
        {
            $this->app->enqueueMessage($e->getMessage(), 'error');
            $this->app->redirect('index.php?option=com_slogin&view=settings');

            return;
        }


        if (is_array($uids) && !empty($uids))
        {
            try
            {
                $db->setQuery(
                    $db->getQuery(true)
                        ->delete($db->quoteName('#__slogin_users'))
                        ->where($db->quoteName('user_id') . ' IN (' . implode(', ', $uids) . ')')
                )->execute();

                $this->app->enqueueMessage(Text::_('COM_SLOGIN_USERS_TABLE_REPAIRED'), 'message');
            }
            catch (\Exception $e)
            {
                $this->app->enqueueMessage($e->getMessage(), 'error');
            }
        }
        else
        {
            $this->app->enqueueMessage(Text::_('COM_SLOGIN_USERS_TABLE_NOTHING_TO_REPAIR'), 'message');
        }

        $this->app->redirect('index.php?option=com_slogin&view=settings');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function remove_slogin_users(): void
    {
        PluginHelper::importPlugin('slogin_integration');

        $dispatcher            = $this->getDispatcher();
        $beforeDeleteEventName = 'onBeforeSloginDeleteSloginUser';
        $afterDeleteEventName  = 'onAfterSloginDeleteSloginUser';

        /** @var UserModel $model */
        $model = $this->getModel('User', 'Administrator');
        /** @var UsersTable $table */
        $table  = $model->getTable();
        $ids    = $this->input->get('cid', [], 'ARRAY');
        $errors = [];

        if (count($ids) > 0)
        {
            foreach ($ids as $id)
            {
                $dispatcher->dispatch($beforeDeleteEventName, new DeleteUserEvent($beforeDeleteEventName, ['id' => (int) $id]));

                if (!$table->delete((int) $id))
                {
                    $errors[] = $table->getError();
                }
                else
                {
                    $dispatcher->dispatch($afterDeleteEventName, new DeleteUserEvent($afterDeleteEventName, ['id' => (int) $id]));
                }
            }
        }

        if (!empty($errors))
        {
            $this->app->enqueueMessage(implode('<br/>', $errors), 'error');
        }
        else
        {
            $this->app->enqueueMessage(Text::_('COM_SLOGIN_USERS_DELETED'), 'message');
        }

        $this->app->redirect('index.php?option=com_slogin&view=users');
    }

    public function remove_joomla_users(): void
    {
        PluginHelper::importPlugin('slogin_integration');

        $dispatcher            = $this->getDispatcher();
        $beforeDeleteEventName = 'onBeforeSloginDeleteUser';
        $afterDeleteEventName  = 'onAfterSloginDeleteUser';

        /** @var UserModel $model */
        $model = $this->getModel('User', 'Administrator');
        /** @var UsersTable $table */
        $table  = $model->getTable();
        $ids    = $this->input->get('cid', [], 'ARRAY');
        $errors = [];
        $user   = new User();
        $db     = $this->getDatabase();

        if (count($ids) > 0)
        {
            foreach ($ids as $id)
            {
                $userId = $db->setQuery(
                    $db->getQuery(true)
                        ->select($db->quoteName('user_id'))
                        ->from($db->quoteName('#__slogin_users'))
                        ->where('id = :user_id')
                        ->bind(':user_id', $id, ParameterType::INTEGER)
                )->loadResult();

                $dispatcher->dispatch($beforeDeleteEventName, new DeleteUserEvent($beforeDeleteEventName, ['id' => (int) $userId]));

                try
                {
                    if (!$table->delete((int) $id))
                    {
                        throw new \RuntimeException($table->getError());
                    }

                    $user->id = $userId;

                    if (!$user->delete())
                    {
                        throw new \RuntimeException($user->getError());
                    }

                    if (!$table->deleteUserRows($userId))
                    {
                        throw new \RuntimeException($table->getError());
                    }

                    $dispatcher->dispatch($afterDeleteEventName, new DeleteUserEvent($afterDeleteEventName, ['id' => (int) $userId]));
                }
                catch (\Exception $e)
                {
                    $errors[] = $e->getMessage();
                }
            }
        }

        if (!empty($errors))
        {
            $this->app->enqueueMessage(implode('<br/>', $errors), 'error');
        }
        else
        {
            $this->app->enqueueMessage(Text::_('COM_SLOGIN_USERS_DELETED'), 'message');
        }

        $this->app->redirect('index.php?option=com_slogin&view=users');
    }
}
