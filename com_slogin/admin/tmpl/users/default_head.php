<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

/**
 * @var \Joomla\Component\SLogin\Administrator\View\Users\HtmlView $this
 */

use Joomla\CMS\Language\Text;

?>
<tr>
    <th width="5%">
        <?php echo Text::_('ID'); ?>
    </th>
    <th width="5%">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);"/>
    </th>
    <th width="10%">
        <?php echo Text::_('COM_SLOGIN_USER_ID'); ?>
    </th>
    <th width="40%">
        <?php echo Text::_('COM_SLOGIN_USER_NAME'); ?>
    </th>
    <th width="20%">
        <?php echo Text::_('COM_SLOGIN_USER_USERNAME'); ?>
    </th>
    <th width="10%">
        <?php echo Text::_('COM_SLOGIN_PROVIDER'); ?>
    </th>
    <th width="10%">
        <?php echo Text::_('COM_SLOGIN_SLOGINID'); ?>
    </th>
</tr>
