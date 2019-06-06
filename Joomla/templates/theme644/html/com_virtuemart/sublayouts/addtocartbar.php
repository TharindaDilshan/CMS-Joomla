<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$product = $viewData['product'];
$app = JFactory::getApplication();
$view = $app->input->getCmd('view', '');

if($view !=='cart'){

if(isset($viewData['rowHeights'])){
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = TRUE;
}

$init = 1;
if(isset($viewData['init'])){
	$init = $viewData['init'];
}

if(!empty($product->min_order_level) and $init<$product->min_order_level){
	$init = $product->min_order_level;
}

$step=1;
if (!empty($product->step_order_level)){
	$step=$product->step_order_level;
	if(empty($product->min_order_level) and !isset($viewData['init'])){
		$init = $step;
	}
}

$maxOrder= '';
if (!empty($product->max_order_level)){
	$maxOrder = ' max="'.$product->max_order_level.'" ';
}

$addtoCartButton = '';
if(!VmConfig::get('use_as_catalog', 0)){
	if(!$product->addToCartButton and $product->addToCartButton!==''){		
		$addtoCartButton = '<button type="submit" name="addtocart" class="addtocart-button btn" value="'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'" title="'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'"><i class="fa fa-shopping-cart"></i><span>'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'</span>
</button>';
	} else {
		$addtoCartButton = $product->addToCartButton;
	}

}
$position = 'addtocart';
//if (!empty($product->customfieldsSorted[$position]) or !empty($addtoCartButton)) {


if (!VmConfig::get('use_as_catalog', 0) ) { ?>

	<div class="addtocart-bar">
	<?php
	// Display the quantity box
	$stockhandle = VmConfig::get ('stockhandle', 'none');
	if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) { ?>
		<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="notify btn"><i class="fa fa-shopping-cart"></i><span><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?>
</span></a>
		<div class="hidden hiddenaddcart">
		<?php $tmpPrice = (float) $product->prices['costPrice'];
		if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>
			<?php if ($product->orderable) { ?>
				<?php if ($view == 'productdetails' && isset($product->customfieldsSorted)) : ?><label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo vmText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label><?php endif; ?>
				<span class="quantity-box">
				<input type="<?php if ($view == 'productdetails' && isset($product->customfieldsSorted)) echo 'text'; else echo 'hidden'; ?>" class="quantity-input js-recalculate" name="quantity[]"
					   onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   value="<?php echo $init; ?>" init="<?php echo $init; ?>" step="<?php echo $step; ?>" <?php echo $maxOrder; ?> />
			</span>
			<?php }

			if(!empty($addtoCartButton)){
				?><span class="addtocart-button">
				<?php echo $addtoCartButton ?>
				</span> <?php
			} ?>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
			<noscript><input type="hidden" name="task" value="add"/></noscript>
			</div>
			 <?php
		}
		
	} else {
		?>
		<div class="hidden hiddennotify">
			<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="notify btn"><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>
		</div>
		<?php
		$tmpPrice = (float) $product->prices['costPrice'];
		if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>
			<?php if ($product->orderable) { ?>
				<?php if ($view == 'productdetails' && isset($product->customfieldsSorted)) : ?><label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo vmText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label><?php endif; ?>
				<span class="quantity-box">
				<input type="<?php if ($view == 'productdetails' && isset($product->customfieldsSorted)) echo 'text'; else echo 'hidden'; ?>" class="quantity-input js-recalculate" name="quantity[]"
					   onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
					   value="<?php echo $init; ?>" init="<?php echo $init; ?>" step="<?php echo $step; ?>" <?php echo $maxOrder; ?> />
			</span>
			<?php }

			if(!empty($addtoCartButton)){
				?><span class="addtocart-button">
				<?php echo $addtoCartButton ?>
				</span> <?php
			} ?>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
			<noscript><input type="hidden" name="task" value="add"/></noscript> <?php
		}
	} ?>

	</div><?php
}
} ?>
