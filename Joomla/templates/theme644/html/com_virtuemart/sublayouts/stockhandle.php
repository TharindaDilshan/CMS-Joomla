<?php
$product = $viewData['product'];
// Availability
$stockhandle = VmConfig::get('stockhandle', 'none');
$product_available_date = substr($product->product_available_date,0,10);
$current_date = date("Y-m-d"); ?>
<div class="availability">
<?php if (($product->product_in_stock - $product->product_ordered) < 1){
	if ($stockhandle == 'risetime' and VmConfig::get('rised_availability') and empty($product->product_availability)){
		echo (file_exists(JPATH_BASE . DS . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability'))) ? JHtml::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability', '7d.gif'), VmConfig::get('rised_availability', '7d.gif'), array('class' => 'availability')) : vmText::_('COM_VIRTUEMART_PRODUCT_AVAILABILITY').': <span>'.vmText::_(VmConfig::get('rised_availability')).'</span>';
	} else if (!empty($product->product_availability)){
		echo (file_exists(JPATH_BASE . DS . VmConfig::get('assets_general_path') . 'images/availability/' . $product->product_availability)) ? JHtml::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . $product->product_availability, $product->product_availability, array('class' => 'availability')) : vmText::_('COM_VIRTUEMART_PRODUCT_AVAILABILITY').': <span>'.vmText::_($product->product_availability).'</span>';
	}
	if ($product_available_date != '0000-00-00' and $current_date < $product_available_date){
		echo '</div><div class="availability_date">'.vmText::_('COM_VIRTUEMART_PRODUCT_AVAILABLE_DATE') .': <span>'. JHtml::_('date', $product->product_available_date, vmText::_('DATE_FORMAT_LC4')).'</span>';
	}
}
else{
	echo vmText::_('COM_VIRTUEMART_PRODUCT_AVAILABILITY').': <span>'.vmText::_('COM_VIRTUEMART_PRODUCT_IN_STOCK').'</span>'; ?>
	<?php if (($product->product_in_stock - $product->product_ordered) < 10){ ?>
	</div>
	<div class="product_in_stock">
		<?php echo JText::sprintf('TPL_PRODUCT_IN_STOCK','<span>'.$product->product_in_stock).'</span>';
	} ?>
<?php } ?>
</div>