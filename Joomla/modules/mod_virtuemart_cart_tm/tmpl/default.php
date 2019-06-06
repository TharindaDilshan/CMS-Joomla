<?php // no direct access

defined('_JEXEC') or die('Restricted access');

$app    = JFactory::getApplication(); 
$template = $app->getTemplate();
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');


//vmJsApi::addJScript("/templates/".$template."/html/mod_virtuemart_cart/js/update_cart.js",false,false);
//vmJsApi::addJScript("/templates/".$template."/html/mod_virtuemart_cart/js/ajaxremoveproduct.js",false,false);

if (false && $data->dataValidated == true) {
	$taskRoute = '&task=confirm';
	$linkName = JText::_('COM_VIRTUEMART_CART_CONFIRM');
} else {
	$taskRoute = '';
	$linkName = JText::_('COM_VIRTUEMART_CART_SHOW');
}
$useSSL = VmConfig::get('useSSL',0);
$useXHTML = true;
$data->cart_show = '<a class="btn" href="'.JRoute::_("index.php?option=com_virtuemart&view=cart".$taskRoute,$useXHTML,$useSSL).'">'.$linkName.'</a>';

//dump ($cart,'mod cart');
// Ajax is displayed in vm_cart_products
// ALL THE DISPLAY IS Done by Ajax using "hiddencontainer" ?>

<!-- Virtuemart 2 Ajax Card -->
<div class="vmCartModule_ajax vmCartModule <?php echo $params->get('moduleclass_sfx'); ?> vmCartModule_<?php echo $module->id; ?>" id="vmCartModule">
	<?php 
preg_match('|<strong>(.*)</strong>|', $data->billTotal, $match);
if(!empty($match)) $data->billTotal = $match[1]; ?>

	<div class="total_products"><a class="<?php if(!count($data->products)) echo ' disabled'; ?>" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart'); ?>" rel="nofollow"><?php echo vmText::sprintf('TPL_CART_MOD', $data->totalProduct); ?></a></div>

	<div class="hiddencontainer" style=" display: none; ">
		<div class="vmcontainer">
			<div class="spinner"></div>
			<div class="image">
			</div>
			<div class="product_row">
				<span class="product_name"><?php echo  $product['product_name'] ?></span>
					<span class="quantity"><?php echo $product["quantity"]; ?></span><div class="prices" style="display:inline;"><?php echo $product["prices"]; ?></div>

				<a class="vm2-remove_from_cart remove" title="<?php echo Jtext::_('TPL_REMOVE_PRODUCT') ?>" onclick="remove_product_cart(this);"><i class="fa fa-times-circle"></i><span class="product_cart_id" style="display:none;"></span></a>
			<div class="product_attributes"></div>
			</div>
		</div>
	</div>
	<div id="vm_cart_products"<?php if(!count($data->products)) echo ' class="empty"'; ?>>
		<div class="text-cart">
			<?php
				echo $data->cart_recent_text;
			?>
		</div> 
	<div class="vm_cart_products">

		
		<?php
			$i = 0;
			$data->products = array_reverse($data->products);
			foreach ($data->products as $key=> $product){
				if ( $i++ == 4 ) break;
				//var_dump($product);
				?>
				<div class="vmcontainer">
				<div class="spinner"></div>
				<div class="image">
				<?php echo $product["image"]; ?>
                </div>
				<div class="product_row">
					<span class="product_name"><?php echo  $product['product_name'] ?></span>
					<span class="quantity"><?php echo $product["quantity"]; ?></span><div class="prices" style="display:inline;"><?php echo $product["prices"]; ?></div>
					
					<a class="vm2-remove_from_cart remove" title="<?php echo Jtext::_('TPL_REMOVE_PRODUCT') ?>" onclick="remove_product_cart(this);"><i class="fa fa-times-circle"></i><span class="product_cart_id" style="display:none;"><?php echo $product["product_cart_id"]; ?></span></a>
				   <?php
					if(!empty($product["product_attributes"])) {
						?>
						<div class="product_attributes"><?php echo $product["product_attributes"]; ?></div>
						<?php
					}
					?>

			</div>
			</div>
		<?php }
		?>
		
	</div>
	
	<div class="totalBox">
		<div class="carttotaltext">
			<?php
			echo $data->carttotaltext;
			?>
		</div>
	 	<div class="total">
			<?php
			print_r($data->cart_total_text);
			 if ($data->totalProduct) echo  $data->billTotal; ?>
		</div>
	</div>
		<div class="show_cart">
			<?php if ($data->totalProduct) echo  $data->cart_show; ?>
		</div>
		<div style="clear:both;"></div>
		<noscript>
		<?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
		</noscript>
		</div>
</div>
<script>
	jQuery(function(){
	
		jQuery('.product_row .vm2-remove_from_cart').live('click',function(){
			jQuery(this).parent().parent().find('.spinner').addClass('removing');						  
		});
		if(jQuery('body').hasClass('mobile')){
			jQuery('#vmCartModule .total_products').on('click',function(){
				//alert('');
				jQuery('#vmCartModule').find('#vm_cart_products').stop().toggleClass('shown');
				return false;
			})
		}
		else{
			jQuery('#vmCartModule').hover(function(){
				jQuery('.vmCartModule_<?php echo $module->id; ?> #vm_cart_products').stop().addClass('shown')
			},function(){
				jQuery('.vmCartModule_<?php echo $module->id; ?> #vm_cart_products').stop().delay(1000)
	                .queue(function(n){
	                    jQuery(this)
	                    .removeClass('shown')
	                    n();
	                });
			})
		}
		jQuery('.vm-customfield-mod .product-field-type-C span').each(function(){
			jQuery(this).text(jQuery(this).text().split('/')[0]);
		});
		//jQuery('.remove').tooltip()
	});
function remove_product_cart(elm) {
	var cart_id = jQuery(elm).children("span.product_cart_id").text();
	jQuery.ajax({
		url: 'index.php?option=com_virtuemart&view=cart&task=delete&removeProductCart=cart_virtuemart_product_id='+cart_id,
		type: 'post',
		data: 'cart_virtuemart_product_id='+cart_id,
		dataType: 'html',
		beforeSend: function(){
                //jQuery('.product_remove_id'+cart_id).closest('.vmcontainer').addClass('removing');
            },
		success: function(html){
			<?php if (($view == 'cart') && ($option == 'com_virtuemart')){?>
				location.reload();
			<?php } ?>
			jQuery('body').trigger('updateVirtueMartCartModule');
			jQuery('.vm-customfield-mod .product-field-type-C span').each(function(){
				jQuery(this).text(jQuery(this).text().split('/')[0]);
			})
		}
}); 
}


</script>