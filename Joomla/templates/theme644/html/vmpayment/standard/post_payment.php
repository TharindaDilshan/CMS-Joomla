<?php
defined ('_JEXEC') or die();

/**
 * @author Valérie Isaksen
 * @version $Id$
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.   - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

?>
<div class="post_payment_payment_name">
	<span class="post_payment_payment_name_title width_50 tright"><?php echo vmText::_ ('VMPAYMENT_STANDARD_PAYMENT_INFO'); ?>: </span>
	<span class="width_50"><?php echo  $viewData["payment_name"]; ?></span>
</div>

<div class="post_payment_order_number">
	<span class="post_payment_order_number_title width_50 tright"><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_NUMBER'); ?>: </span>
	<span class="width_50"><strong><?php echo  $viewData["order_number"]; ?></strong></span>
</div>

<div class="post_payment_order_total">
	<span class="post_payment_order_total_title width_50 tright"><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_PRINT_TOTAL'); ?>: </span>
	<span class="width_50"><strong><?php echo  $viewData['displayTotalInPaymentCurrency']; ?></strong></span>
</div>
<a class="btn" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number='.$viewData["order_number"].'&order_pass='.$viewData["order_pass"], false)?>"><?php echo vmText::_('COM_VIRTUEMART_ORDER_VIEW_ORDER'); ?></a>






