<?php

defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

/**
 * @var \Joomla\Component\SLogin\Administrator\View\Users\HtmlView $this
 */

$wa = $this->document->getWebAssetManager();
$wa->addInlineStyle('.icon-48-users {background: url("../media/com_slogin/icon_48x48.png")}');

HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('dropdown.init');
HTMLHelper::_('formbehavior.chosen', 'select');
?>
<form
    action="<?php echo Route::_('index.php?option=com_slogin&view=users'); ?>"
    method="post"
    name="adminForm"
    id="adminForm"
>
    <?php echo $this->loadTemplate('filter'); ?>
    <div class="clr"></div>
    <table class="adminlist table table-striped">
        <thead><?php echo $this->loadTemplate('head'); ?></thead>
        <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
        <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
    </table>
    <div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
