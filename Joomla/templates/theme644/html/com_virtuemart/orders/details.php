<?php
/**
*
* Order detail view
*
* @package	VirtueMart
* @subpackage Orders
* @author Oscar van Eijk, Valerie Isaksen
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details.php 8769 2015-03-02 16:47:22Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);

JHtml::stylesheet('vmpanels.css', JURI::root().'components/com_virtuemart/assets/css/');
if($this->print){ ?>

		<body onload="javascript:print();">
		<div><img src="<?php  echo JURI::root() . $this-> vendor->images[0]->file_url ?>"></div>
		<h4><?php  echo $this->vendor->vendor_store_name; ?></h4>
		<?php  echo $this->vendor->vendor_name .' - '.$this->vendor->vendor_phone ?>
		<h4><?php echo vmText::_('COM_VIRTUEMART_ACC_ORDER_INFO'); ?></h4>
		<div class='spaceStyle'>
		<?php echo $this->loadTemplate('order'); ?>
		</div>
		<div class='spaceStyle'>
		<?php echo $this->loadTemplate('items'); ?>
		</div>
		<?php if(!class_exists('VirtuemartViewInvoice')) require_once(VMPATH_SITE .DS. 'views'.DS.'invoice'.DS.'view.html.php');
		echo VirtuemartViewInvoice::replaceVendorFields($this->vendor->vendor_letter_footer_html, $this->vendor); ?>
		</body>
<?php } else { ?>
<div id="com_virtuemart">
	<h4><?php echo vmText::_('COM_VIRTUEMART_ACC_ORDER_INFO');
		/* Print view URL */
		$details_link = "<a class=\"print_link\" href=\"javascript:void window.open('$this->details_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
		//$details_link .= '<span class="hasTip print_32" title="' . vmText::_('COM_VIRTUEMART_PRINT') . '">&nbsp;</span></a>';
		$button = 'system/printButton.png';
		$details_link .= '<i class="fa fa-print"></i>';
		$details_link  .=  '</a>';
		echo $details_link; ?>
	</h4>
	<?php if($this->order_list_link){ ?>
	<div class='spaceStyle'>
		<a class="btn" href="<?php echo $this->order_list_link ?>" rel="nofollow"><i class="fa fa-chevron-left"></i> <?php echo vmText::_('COM_VIRTUEMART_ORDERS_VIEW_DEFAULT_TITLE'); ?></a>
	</div>
	<?php }?>
	<div class='spaceStyle'>
	<?php echo $this->loadTemplate('order'); ?>
	</div>
	<div class='spaceStyle'>
		<?php $tabarray = array();
		$tabarray['items'] = 'COM_VIRTUEMART_ORDER_ITEM';
		$tabarray['history'] = 'COM_VIRTUEMART_ORDER_HISTORY';
	shopFunctionsF::buildTabs ( $this, $tabarray); ?>
	</div>
</div>
<?php } ?>