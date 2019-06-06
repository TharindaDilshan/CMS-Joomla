<?php
/**
*
* Layout for the login
*
* @package	VirtueMart
* @subpackage User
* @author Max Milbers, George Kostopoulos
*
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 4431 2011-10-17 grtrustme $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

//set variables, usually set by shopfunctionsf::getLoginForm in case this layout is differently used
if (!isset( $this->show )) $this->show = TRUE;
if (!isset( $this->from_cart )) $this->from_cart = FALSE;
if (!isset( $this->order )) $this->order = FALSE ;


if (empty($this->url)){
	$url = vmURI::getCleanUrl();
} else{
	$url = $this->url;
}

$user = JFactory::getUser();

if ($this->show and $user->id == 0  ) {

	//Extra login stuff, systems like openId and plugins HERE
    if (JPluginHelper::isEnabled('authentication', 'openid')) {
        $lang = JFactory::getLanguage();
        $lang->load('plg_authentication_openid', JPATH_ADMINISTRATOR);
        $langScript = '
//<![CDATA[
'.'var JLanguage = {};' .
                ' JLanguage.WHAT_IS_OPENID = \'' . vmText::_('WHAT_IS_OPENID') . '\';' .
                ' JLanguage.LOGIN_WITH_OPENID = \'' . vmText::_('LOGIN_WITH_OPENID') . '\';' .
                ' JLanguage.NORMAL_LOGIN = \'' . vmText::_('NORMAL_LOGIN') . '\';' .
                ' var comlogin = 1;
//]]>
                ';
		vmJsApi::addJScript('login_openid',$langScript);
        JHtml::_('script', 'openid.js');
    }

    $html = '';
    JPluginHelper::importPlugin('vmpayment');
    $dispatcher = JDispatcher::getInstance();
    $returnValues = $dispatcher->trigger('plgVmDisplayLogin', array($this, &$html, $this->from_cart));

    if (is_array($html)) {
		foreach ($html as $login) {
		    echo $login.'<br>';
		}
    }
    else {
		echo $html;
    }

    //end plugins section

    //anonymous order section
    if ($this->order) { ?>
    <div class="row-fluid">
	<div class="order-view span6">

	    <h4><?php echo vmText::_('COM_VIRTUEMART_ORDER_ANONYMOUS') ?></h4>
	    <form action="<?php echo JRoute::_( 'index.php', 1, $this->useSSL); ?>" method="post" name="com-login">

	    	<div class="width30 floatleft order-pad" id="com-form-order-number">
	    		<label for="order_number"><?php echo vmText::_('COM_VIRTUEMART_ORDER_NUMBER') ?></label>
	    		<input type="text" id="order_number" name="order_number" class="inputbox" size="18" alt="order_number">
	    	</div>
	    	<div class="width30 floatleft order-pad" id="com-form-order-pass">
	    		<label for="order_pass"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PASS') ?></label>
	    		<input type="text" id="order_pass" name="order_pass" class="inputbox" size="18" alt="order_pass" value="p_">
	    	</div>
	    	<div class="width30 floatleft" id="com-form-order-submit">
	    		<button type="submit" name="Submitbuton" class="btn" value="<?php echo vmText::_('COM_VIRTUEMART_ORDER_BUTTON_VIEW') ?>"><?php echo vmText::_('COM_VIRTUEMART_ORDER_BUTTON_VIEW') ?></button>
	    	</div>
	    	<div class="clr"></div>
	    	<input type="hidden" name="option" value="com_virtuemart" />
	    	<input type="hidden" name="view" value="orders" />
	    	<input type="hidden" name="layout" value="details" />
	    	<input type="hidden" name="return" value="" />

	    </form>

	</div>
    <div class="span6">
<?php }
    // XXX style CSS id com-form-login ?>
    
    <form id="com-form-login" action="<?php echo JRoute::_('index.php', $this->useXHTML, $this->useSSL); ?>" method="post" name="com-login">
    <fieldset class="userdata">
	<?php if (!$this->from_cart){ ?>
		<h4><?php echo vmText::_('COM_VIRTUEMART_ORDER_CONNECT_FORM'); ?></h4></br>
<div class="clear"></div>
<?php } ?>
<div class="controls">
        <div class="input-prepend">
            <span class="add-on">
              <span class="fa fa-user hasTooltip" title="<?php echo vmText::_('COM_VIRTUEMART_USERNAME') ?>"></span>
            </span>
            <input type="text" name="username" class="inputbox" placeholder="<?php echo vmText::_('COM_VIRTUEMART_USERNAME'); ?>" required>
	</div>
    </div>
    <div class="controls">
        <div class="input-prepend">
            <span class="add-on">
              <span class="fa fa-lock hasTooltip" title="<?php echo vmText::_('COM_VIRTUEMART_PASSWORD') ?>"></span>
            </span>
            <input id="modlgn-passwd" type="password" name="password" class="inputbox" placeholder="<?php echo vmText::_('COM_VIRTUEMART_PASSWORD'); ?>" required>
		</div>
        </div>

        <div id="com-form-login-buttons">
            <button type="submit" name="Submit" class="btn" value="<?php echo vmText::_('COM_VIRTUEMART_LOGIN') ?>"><i class="fa fa-sign-in"></i> <?php echo vmText::_('COM_VIRTUEMART_LOGIN') ?></button>
             <?php $usersConfig = JComponentHelper::getParams('com_users');
            if ($usersConfig->get('allowUserRegistration')) : ?>
            <a class="btn btn-primary register" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
            <?php endif; ?>
        </div>
        <div id="com-form-login-remember">
            <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
            <input type="checkbox" id="remember" name="remember" class="inputbox" value="yes">
            <label for="remember"><?php echo $remember_me = vmText::_('JGLOBAL_REMEMBER_ME') ?></label>
            <?php endif; ?>
            </div>
        </fieldset>
        <div class="clr"></div>

        <div class="reset_remind">
      <?php echo JText::_('TPL_FORGOT'); ?>
      <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" class="hasTooltip"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a> /
      <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="hasTooltip"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>?
      </div>

        <div class="clr"></div>

		<input type="hidden" name="task" value="user.login">
        <input type="hidden" name="option" value="com_users">
        <input type="hidden" name="return" value="<?php echo base64_encode($url) ?>">
        <?php echo JHtml::_('form.token'); ?>
    </form>

<?php  } else if ($user->id){ ?>

	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="login" id="form-login">
        <?php echo JHtml::_('link', JRoute::_('index.php?option=com_users&view=profile'), $user->name); ?><br><br>
        <button type="submit" name="Submit" class="btn" value="<?php echo vmText::_( 'COM_VIRTUEMART_BUTTON_LOGOUT'); ?>"><?php echo vmText::_( 'COM_VIRTUEMART_BUTTON_LOGOUT'); ?></button>
        <input type="hidden" name="option" value="com_users">

        <input type="hidden" name="task" value="user.logout">

        <?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="return" value="<?php echo base64_encode($url) ?>">
    </form>
    <?php if ($this->order) { ?>
    </div>
    </div>
    <?php } ?>
<?php } ?>