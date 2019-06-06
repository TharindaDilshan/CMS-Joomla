<?php
/**
 *
 * Layout for the payment selection
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: select_payment.php 8686 2015-02-05 19:43:41Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
$lang = JFactory::getLanguage();
$extension = 'com_contact';
$base_dir = JPATH_SITE;
$language_tag = 'en-GB';
$reload = true;
$user = JFactory::getUser();
$lang->load($extension, $base_dir, $language_tag, $reload);
$addClass="";


if (VmConfig::get('oncheckout_show_steps', 1)) { ?>
    <div id="com_virtuemart">
		<ul class="steps">
		<li><span><?php echo vmText::_ ('TPL_SUMMARY'); ?></span></li>
		<?php if ($user->guest) { ?>
		<li><span><?php echo vmText::_ ('JLOGIN'); ?></span></li>
		<?php } ?>
		<li><span><?php echo vmText::_ ('TPL_SHIPPING'); ?></span></li>
		<li class="current"><span><?php echo vmText::_ ('COM_VIRTUEMART_CART_PAYMENT'); ?></span></li>
		<li><span><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_CONFIRM_MNU'); ?></span></li>
	</ul>
<?php }

if ($this->layoutName!='default') {
	$headerLevel = 'h4';
	if($this->cart->getInCheckOut()){
		$buttonclass = 'button vm-button-correct';
	} else {
		$buttonclass = 'default';
	}
?>
	<form method="post" id="paymentForm" name="choosePaymentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate <?php echo $addClass ?>">
<?php } else {
		$buttonclass = 'vm-button-correct';
	}

	echo "<".$headerLevel.">".vmText::_('COM_VIRTUEMART_CART_SELECT_PAYMENT').' '.vmText::_('COM_VIRTUEMART_CART_SELECTED_PAYMENT_SELECT')."</".$headerLevel.">";


?>

<?php
     if ($this->found_payment_method ) { ?>
    <fieldset>
		<?php foreach ($this->paymentplugins_payments as $paymentplugin_payments) {
		    if (is_array($paymentplugin_payments)) {
			foreach ($paymentplugin_payments as $paymentplugin_payment) {
			    echo '<p>'.$paymentplugin_payment.'</p>';
			}
		    }
		} ?>
    </fieldset>
    <?php } else {
	 echo "<".$headerLevel.">".$this->payment_not_found_text."</".$headerLevel.">";
    } ?>

<div class="buttonBar-right">

   <?php   if ($this->layoutName!='default') { ?>
<button class="btn <?php echo $buttonclass ?> cancel" type="reset" onClick="window.location.href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&task=cancel'); ?>'" ><i class="fa fa-chevron-left"></i> <?php echo vmText::_('COM_VIRTUEMART_CANCEL'); ?></button>
	<?php  } ?>
<button name="updatecart" class="btn <?php echo $buttonclass ?>" type="submit"><?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?> <i class="fa fa-chevron-right"></i></button>
    </div>

<?php if ($this->layoutName!='default') {
?>    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="view" value="cart" />
    <input type="hidden" name="task" value="updatecart" />
    <input type="hidden" name="controller" value="cart" />
</form>
<?php
}
if (VmConfig::get('oncheckout_show_steps', 1)) { ?>
</div>
<?php } ?>