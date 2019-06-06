<?php
/**
 *
 * Enter address data for the cart, when anonymous users checkout
 *
 * @package    VirtueMart
 * @subpackage User
 * @author Oscar van Eijk, Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_address.php 8768 2015-03-02 12:22:14Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
$lang = JFactory::getLanguage();
$extension = 'com_contact';
$base_dir = JPATH_SITE;
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);
$document = JFactory::getDocument();

JHtml::_('bootstrap.framework');
$document->addScript('templates/'.$template->template.'/js/jquery.validate.min.js');
$document->addScript('templates/'.$template->template.'/js/additional-methods.min.js');

// Implement Joomla's form validation
JHtml::stylesheet ('vmpanels.css', JURI::root () . 'components/com_virtuemart/assets/css/');

if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
$this->cart = VirtueMartCart::getCart();
$url = 0;
if ($this->cart->_fromCart or $this->cart->getInCheckOut()) {
	$rview = 'cart';
}
else {
	$rview = 'user';
}
function renderControlButtons($view,$rview){
	?>
<div class="control-buttons">
	<?php


	if ($view->cart->getInCheckOut() || $view->address_type == 'ST') {
		$buttonclass = 'default';
	}
	else {
		$buttonclass = 'button vm-button-correct';
	}


	if (VmConfig::get ('oncheckout_show_register', 1) && $view->userDetails->JUser->id == 0 && !VmConfig::get ('oncheckout_only_registered', 0) && $view->address_type == 'BT' and $rview == 'cart') {
		echo '<div id="reg_text">'.vmText::sprintf ('COM_VIRTUEMART_ONCHECKOUT_DEFAULT_TEXT_REGISTER', vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'), vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST')).'</div>';			}
	else {
		//echo vmText::_('COM_VIRTUEMART_REGISTER_ACCOUNT');
	}
	if (VmConfig::get ('oncheckout_show_register', 1) && $view->userDetails->JUser->id == 0 && $view->address_type == 'BT' and $rview == 'cart') {
		?>
		<button name="register" class="btn <?php echo $buttonclass ?>" type="submit" onclick="javascript:return myValidator(true);"
				title="<?php echo vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?>"><?php echo vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?></button>
		<?php if (!VmConfig::get ('oncheckout_only_registered', 0)) { ?>
			<button name="save" class="btn <?php echo $buttonclass ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?>" type="submit"
					onclick="javascript:return myValidator(false);"><?php echo vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?></button>
		<?php } ?>
		<button class="btn default cancel" type="reset"
				onclick="window.location.href='<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=' . $rview.'&task=cancel'); ?>'"><?php echo vmText::_ ('COM_VIRTUEMART_CANCEL'); ?></button>
	<?php
	}
	else {
		?>
		<button class="btn <?php echo $buttonclass ?>" type="submit"
				onclick="javascript:return myValidator(userForm,true);"><?php echo vmText::_ ('COM_VIRTUEMART_SAVE'); ?></button>
		<button class="btn default cancel" type="reset"
				onclick="window.location.href='<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=' . $rview.'&task=cancel'); ?>'"><?php echo vmText::_ ('COM_VIRTUEMART_CANCEL'); ?></button>
	<?php } ?>
</div>
<?php
} ?>


<div id="com_virtuemart">
<ul class="steps">
	<li><span><?php echo vmText::_ ('TPL_SUMMARY'); ?></span></li>
	<li class="current"><span><?php echo vmText::_ ('JLOGIN'); ?></span></li>
	<li><span><?php echo vmText::_ ('TPL_SHIPPING'); ?></span></li>
	<li><span><?php echo vmText::_ ('COM_VIRTUEMART_CART_PAYMENT'); ?></span></li>
	<li><span><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_CONFIRM_MNU'); ?></span></li>
</ul>
<h3><?php echo $this->page_title ?></h3>
<?php 
$task = '';
if ($this->cart->getInCheckOut()){
	//$task = '&task=checkout';
}
$url = 'index.php?option=com_virtuemart&view='.$rview.$task;?>
<div class="row-fluid">

<div class="span8">
<form method="post" id="userForm" name="userForm" class="form-validate" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user',$this->useXHTML,$this->useSSL) ?>" >
<fieldset>
	<<?php echo $template->params->get('categoryItemHeading'); ?>><?php
		if ($this->address_type == 'BT') {
			echo vmText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL');
		}
		else {
			echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL');
		}
		?>
	</<?php echo $template->params->get('categoryItemHeading'); ?>>

<?php // captcha addition
	if(VmConfig::get ('reg_captcha') && JFactory::getUser()->guest == 1){
		$captcha_visible = vRequest::getVar('captcha');
		$hide_captcha = (VmConfig::get ('oncheckout_only_registered') or $captcha_visible) ? '' : 'style="display: none;"';
		?>
		<fieldset id="recaptcha_wrapper" <?php echo $hide_captcha ?>>
			<?php if(!VmConfig::get ('oncheckout_only_registered')) { ?>
				<span class="userfields_info"><?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_CAPTCHA'); ?></span>
			<?php } ?>
			<?php
			echo $this->captcha; ?>
		</fieldset>
<?php }
	// end of captcha addition

	if (!class_exists ('VirtueMartCart')) {
		require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
	}

	if (count ($this->userFields['functions']) > 0) {
		echo '<script language="javascript">' . "\n";
		echo join ("\n", $this->userFields['functions']);
		echo '</script>' . "\n";
	}

	echo $this->loadTemplate ('userfields');
	renderControlButtons($this,$rview);
	if ($this->userDetails->JUser->get ('id')) {
		echo $this->loadTemplate ('addshipto');
	} ?>
	<input type="hidden" name="option" value="com_virtuemart"/>
	<input type="hidden" name="view" value="user"/>
	<input type="hidden" name="controller" value="user"/>
	<input type="hidden" name="task" value="saveUser"/>
	<input type="hidden" name="layout" value="<?php echo $this->getLayout (); ?>"/>
	<input type="hidden" name="address_type" value="<?php echo $this->address_type; ?>"/>
	<?php if (!empty($this->virtuemart_userinfo_id)) {
		echo '<input type="hidden" name="shipto_virtuemart_userinfo_id" value="' . (int)$this->virtuemart_userinfo_id . '" />';
	}
	echo JHtml::_ ('form.token');
	?>

</fieldset>
</form>
</div>
	<div class="span4">
<div class="login_form">
<?php if($this->userDetails->JUser->id == 0){
	echo '<'.$template->params->get('categoryItemHeading').'>'.vmText::_ ('TPL_ALREADY_REGISTERED').'</'.$template->params->get('categoryItemHeading').'>';
}
echo shopFunctionsF::getLoginForm (TRUE, FALSE, $url);
?>
</div>
</div>
</div>

<?php //renderControlButtons($this,$rview); ?>
<script>
  jQuery(document).bind('ready', function(){
  	validator = jQuery('#userForm').validate({
    	wrapper: 'mark',
    	rules: {
    		virtuemart_state_id: {
    			required: true
    		}
    	},
    	errorPlacement: function(error, element) {
		    error.insertBefore(element);
		  }
    })
  	jQuery('button[name=save]').on('click', function(){
  		jQuery('#userForm [name="username"], #userForm [name="name"], #userForm [name="password"], #userForm [name="password2"]').each(function () {
            jQuery(this).rules('remove');
        });
	    jQuery('#userForm').submit();
  	})
  	jQuery('button[name=register]').on('click', function(){
  		jQuery('#userForm [name="username"], #userForm [name="name"]').each(function () {
            jQuery(this).rules('add', {
                required: true
            });
        });
        jQuery('#userForm #password_field').rules('add',{
        	required: true
        })
        jQuery('#userForm #password2_field').rules('add',{
        	required: true,
        	equalTo: "#userForm #password_field"
        })
	    jQuery('#userForm').submit();
  	})
  	jQuery('button[name=save_2]').on('click', function(){
	    jQuery('#userForm').submit();
  	})
  })  
</script>
</div>