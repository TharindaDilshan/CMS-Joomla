<?php
/**
 *
 * Layout for the shopping cart
 *
 * @package    VirtueMart
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
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

if(count($this->cart->products) > 0){

$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
$lang = JFactory::getLanguage();
$extension = 'com_contact';
$base_dir = JPATH_SITE;
$language_tag = 'en-GB';
$reload = true;
$user = JFactory::getUser();
$lang->load($extension, $base_dir, $language_tag, $reload);
vmJsApi::addJScript('vm.STisBT',"
	jQuery(document).ready(function($) {

		if ( $('#STsameAsBTjs').is(':checked') ) {
			$('#output-shipto-display').hide();
		} else {
			$('#output-shipto-display').show();
		}
		$('#STsameAsBTjs').click(function(event) {
			if($(this).is(':checked')){
				$('#STsameAsBT').val('1') ;
				$('#output-shipto-display').hide();
			} else {
				$('#STsameAsBT').val('0') ;
				$('#output-shipto-display').show();
			}
			var form = jQuery('#checkoutFormSubmit');
			document.checkoutForm.submit();
		});
	});
");

vmJsApi::addJScript('vm.checkoutFormSubmit','
	jQuery(document).ready(function($) {
		jQuery(this).vm2front("stopVmLoading");
		jQuery("#checkoutFormSubmit").bind("click dblclick", function(e){
			jQuery(this).vm2front("startVmLoading");
			e.preventDefault();
			jQuery(this).attr("disabled", "true");
			jQuery(this).removeClass( "vm-button-correct" );
			jQuery(this).addClass( "vm-button" );
			jQuery(this).fadeIn( 400 );
			var name = jQuery(this).attr("name");
			$("#checkoutForm").append("<input name=\""+name+"\" value=\"1\" type=\"hidden\">");
			$("#checkoutForm").submit();
		});
	});
');

$virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId ();
$categoryStr = '';
if ($virtuemart_category_id) {
	$categoryStr = '&virtuemart_category_id=' . $virtuemart_category_id;
}

$ItemidStr = '';
$Itemid = shopFunctionsF::getLastVisitedItemId();
if(!empty($Itemid)){
	$ItemidStr = '&Itemid='.$Itemid;
}

$this->continue_link = JRoute::_ ('index.php?option=com_virtuemart&view=category' . $categoryStr.$ItemidStr, FALSE);
$this->continue_link_html = '<a class="continue_link btn" href="' . $this->continue_link . '"><i class="fa fa-chevron-left"></i> ' . vmText::_ ('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';

$this->addCheckRequiredJs();
 ?>
<div id="com_virtuemart">
<div class="cart-view">
	<div class="vm-cart-header-container">
		<div class="vm-cart-header">
			<h3><?php echo vmText::_ ('COM_VIRTUEMART_CART_TITLE'); ?></h3>
			<div class="payments-signin_button" ></div>
		</div>
		<?php 
		if (VmConfig::get ('oncheckout_show_steps', 1)) { ?>
		<ul class="steps">
			<li<?php if($this->checkout_task === 'checkout') { ?> class="current"<?php } ?>><span><?php echo vmText::_ ('TPL_SUMMARY'); ?></span></li>
			<?php if ($user->guest) { ?>
			<li><span><?php echo vmText::_ ('JLOGIN'); ?></span></li>
			<?php } ?>
			<li><span><?php echo vmText::_ ('TPL_SHIPPING'); ?></span></li>
			<li><span><?php echo vmText::_ ('COM_VIRTUEMART_CART_PAYMENT'); ?></span></li>
			<li<?php if($this->checkout_task === 'confirm') { ?> class="current"<?php } ?>><span><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_CONFIRM_MNU'); ?></span></li>
		</ul>
	<?php } ?>
		<div class="clear"></div>
	</div>

	<?php //echo shopFunctionsF::getLoginForm ($this->cart, FALSE);

	// This displays the form to change the current shopper
	if ($this->allowChangeShopper){
		echo $this->loadTemplate ('shopperform');
	}

	$taskRoute = '';
	?><form method="post" id="checkoutForm" name="checkoutForm" class="form-vertical" action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
		<?php
		if(VmConfig::get('multixcart')=='byselection'){
			if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN . '/helpers/shopfunctions.php');
			echo shopFunctions::renderVendorFullVendorList($this->cart->vendorId);
			?><input type="submit" name="updatecart" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="btn button"  style="margin-left: 10px;"/><?php
		}
		// This displays the pricelist MUST be done with tables, because it is also used for the emails
		echo $this->loadTemplate ('pricelist');
		echo $this->loadTemplate ('address');

		if (!empty($this->checkoutAdvertise)) {
			?> <div id="checkout-advertise-box"> <?php
			foreach ($this->checkoutAdvertise as $checkoutAdvertise) {
				?>
				<div class="checkout-advertise">
					<?php echo $checkoutAdvertise; ?>
				</div>
				<?php
			}
			?></div><?php
		}

		echo $this->loadTemplate ('cartfields');

		?>

		<div class="vm-continue-shopping cance-shop">
			<?php // Continue Shopping Button
			if (!empty($this->continue_link_html)) {
				echo $this->continue_link_html;
			} ?>
		</div>
		<div class="checkout-button-bottom"> <?php
		if ($this->cart->getDataValidated()) {
				if($this->cart->_inConfirm){
					$text = vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM');
					$this->checkout_task = 'cancel';
				} else {
					$text = vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');
					$this->checkout_task = 'confirm';
				}
			} else {
				$text = vmText::_('COM_VIRTUEMART_CHECKOUT_TITLE');
				$this->checkout_task = 'checkout';
			}
			$this->checkout_link_html = '<button type="submit"  id="checkoutFormSubmit" name="'.$this->checkout_task.'" value="1" class="btn vm-button-correct" >' . $text . '</button>';
			echo $this->checkout_link_html;
		?></div>

		<?php // Continue and Checkout Button END ?>
		<input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
		<input type='hidden' name='task' value='updatecart'/>
		<input type='hidden' name='option' value='com_virtuemart'/>
		<input type='hidden' name='view' value='cart'/>
	</form>
</div>
</div>
<?php } else {
	JFactory::getApplication()->enqueueMessage(vmText::_('COM_VIRTUEMART_EMPTY_CART'), 'notice');
}?>