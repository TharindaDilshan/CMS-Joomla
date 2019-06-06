<?php
/**
 *
 * Show Notify page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_reviews.php 5428 2012-02-12 04:41:22Z electrocity $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die ('Restricted access');
$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
$document = JFactory::getDocument();
JHtml::_('bootstrap.framework');
$document->addScript('templates/'.$template->template.'/js/jquery.validate.min.js');
$document->addScript('templates/'.$template->template.'/js/additional-methods.min.js');
?>
<div id="com_virtuemart">
	<form method="post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$this->product->virtuemart_product_id.'&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE); ?>" name="notifyform" id="notifyform">
		<h4><?php echo vmText::_('COM_VIRTUEMART_CART_NOTIFY'); ?></h4>
		<div class="list-reviews">
			<?php echo vmText::sprintf('COM_VIRTUEMART_CART_NOTIFY_DESC', $this->product->product_name); ?>
			<br>
			<br>
			<div class="clear"></div>
		</div>
		<div class="controls">
			<input type="email" name="notify_email" value="<?php echo $this->user->email; ?>" placeholder="<?php echo vmText::_('COM_VIRTUEMART_USER_FORM_EMAIL'); ?>" required>
			<button type="submit" name="notifycustomer"  class="btn" value="<?php echo vmText::_('COM_VIRTUEMART_CART_NOTIFY'); ?>" title="<?php echo vmText::_('COM_VIRTUEMART_CART_NOTIFY'); ?>"><?php echo vmText::_('COM_VIRTUEMART_CART_NOTIFY'); ?></button>
		</div>
		<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>">
		<input type="hidden" name="option" value="com_virtuemart">
		<input type="hidden" name="virtuemart_category_id" value="<?php echo vRequest::getInt('virtuemart_category_id'); ?>">
		<input type="hidden" name="virtuemart_user_id" value="<?php echo $this->user->id; ?>">
		<input type="hidden" name="task" value="notifycustomer">
		<input type="hidden" name="controller" value="productdetails">
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<script>
		jQuery(document).bind('ready', function(){
		    validator = jQuery('#notifyform').validate({
		    	wrapper: 'mark'
		    })
		})
	</script>
</div>