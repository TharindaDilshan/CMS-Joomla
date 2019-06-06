<?php
/**
 * field tos
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filter.filteroutput' );
$products_per_row = $viewData['products_per_row'];
$currency = $viewData['currency'];
$showRating = $viewData['showRating'];
$tabshome = $viewData['tabshome'];
$prodslider = $viewData['prodslider'];
$layaoutwhishlist = $viewData['layaoutwhishlist'];
//print_r($tabshome);
if ($tabshome == 'tabshome'){
	$listing_class = 'tab-pane fade in';
	$link_params = 'role="tab" data-toggle="tab"';
} else {
	$listing_class = '';
	$link_params = '';
}


$app    = JFactory::getApplication();
$template = $app->getTemplate(true);
$params = $template->params;

$themeLayout = 1;

switch ($themeLayout) {
  case '0':
    $rowClass = 'row';
    break;

  case '1':
    $rowClass = 'row-fluid';
    break;
  
  default:
    $rowClass = 'row';
    break;
}

$ItemidStr = '';
$Itemid = shopFunctionsF::getLastVisitedItemId();
if(!empty($Itemid)){
	$ItemidStr = '&Itemid='.$Itemid;
}
//print_r($ItemidStr);
if ($prodslider == 'prodslider') {	
	echo '<div class="slider-box">';
}
if ($tabshome == 'tabshome'){	
//var_dump($viewData['products']['featured']); ?>
<div class="bs-example-tabs" data-example-id="togglable-tabs">
<ul class="nav nav-tab" role="tablist">
	<?php foreach ($this->products as $type => $productList ) { ?>	
	<li role="presentation" class="heading-style-3 <?php echo $type ?> <?php echo $type == 'featured' ? 'active' : '' ?>">
		<a id="<?php echo $type ?>-tab" aria-controls="<?php echo $type ?>-view" href="#<?php echo $type ?>-view" <?php echo $link_params; ?>>

			<?php
			if($type !== 'topten'){
			 	echo JText::_('COM_VIRTUEMART_'.$type.'_PRODUCT');
			 }else {
				echo JText::_('TPL_VIRTUEMART_TOP_PRODUCTS');
			 }
			?>
		</a>
	</li>

	<?php } ?>
	<div class="clearfix both"></div>
</ul>
<div class="tab-content">
<?php }
foreach ($viewData['products'] as $type => $products ) {

	// Category and Columns Counter
	$col = 1;
	$nb = 1;
	$row = 1;
	$BrowseTotalProducts = count($products);
	//vmdebug('amount to browse '.$type,$BrowseTotalProducts);
	if(!empty($type)){

		$productTitle = vmText::_('COM_VIRTUEMART_'.$type.'_PRODUCT');
		?>
		<div id="<?php echo $type ?>-view" class="<?php echo $listing_class; ?> <?php echo $type ?>-view <?php echo $type == 'featured' ? 'active' : '' ?>" id="<?php echo $type ?>">
		<?php // Start the Output
	}

	$rowHeights = array();
	$rowsHeight = array();
	foreach($products as $product){

		$priceRows = 0;
		//Lets calculate the height of the prices
		foreach($currency->_priceConfig as $name=>$values){
			if(!empty($currency->_priceConfig[$name][0])){
				if(!empty($product->prices[$name]) or $name == 'billTotal' or $name == 'billTaxAmount'){
					$priceRows++;
				}
			}
		}
		$rowHeights[$row]['price'][] = $priceRows;
		$position = 'addtocart';
		if(!empty($product->customfieldsSorted[$position])){
			$customs = count($product->customfieldsSorted[$position]);
		} else {
			$customs = 0;
		}
		$rowHeights[$row]['customs'][] = $customs;
		$nb ++;

		if ($col == $products_per_row || $nb>$BrowseTotalProducts) {
			$col = 1;
			$max = 0;
			foreach($rowHeights[$row] as $type => $cols){
				//vmdebug('my rows heights $type',$type);
				
				$rowsHeight[$row][$type] = 0;
				foreach($cols as $col){
					$rowsHeight[$row][$type] =  max($rowsHeight[$row][$type],$col);
					//vmdebug('my rows  $cols',$col);
				}
				//vmdebug('my rows  $type',$type);
			}

			$rowHeights = array();
			$row++;
		} else {
			$col ++;
		}

	}

	//vmdebug('my rows heights',$rowsHeight);
	$col = 1;
	$nb = 1;
	$row = 1;

	foreach ( $products as $product ) {

		// this is an indicator wether a row needs to be opened or not
		if ($col == 1) {
			if ($prodslider == 'prodslider') {	
		 ?>
			<div class='itemslide'>
		<?php } else { ?>		
			<div class="<?php echo $rowClass; ?>">
		<?php
			}  
		}

		// Show the vertical seperator
		if ($nb == $products_per_row or $nb % $products_per_row == 0) {
			$show_vertical_separator = ' ';
		}
		if($layaoutwhishlist) { 
		 	$idprod = 'wishlists_prod_'.$product->virtuemart_product_id.'';
		 	$styleblockremove = 'block';
		 	$styleblockadd = 'none';

		 }else {
	 		$idprod = 'producthorizon';
	 		$styleblockremove = 'none';
	 		$styleblockadd = 'block';
		 }
		// Show Products ?>
	<div id="<?php echo $idprod; ?>" class="product vm-product-horizon <?php if ($prodslider == 'prodslider') { echo 'slide';}else{echo 'vm-col span' . floor(12/$products_per_row);}  ?>">
		<?php 
        	//$class="";
        	//var_dump($product->prices['priceWithoutTax']);
			if ($product->prices['discountedPriceWithoutTax'] != $product->prices['priceWithoutTax']) {
				$class=" with_discount_col";
			} else {
				$class="";
			}
        ?>
		<div class="prod-box<?php echo $class; ?>">
			
      <div class="vm-product-media-rating">
        <div class="vm-product-media-container">
			<?php 
			if ($product->prices['discountedPriceWithoutTax'] != $product->prices['priceWithoutTax']) {
				echo '<div class="sale">'.JText::_('TPL_VIRTUEMART_SALE').'</div>';
			}
       		?>
        	<?php 
        		if ($product->prices['discountedPriceWithoutTax'] != $product->prices['priceWithoutTax']) {
				
				}
        	?>
					<a href="<?php echo $product->link.$ItemidStr; ?>"><?php echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false); ?></a>

				</div>
       <?php /*
         if ( VmConfig::get ('display_stock', 1)) { ?>
        <div class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></div>
          <?php } ?>
          <?php //echo shopFunctionsF::renderVmField('stockhandle',array('product'=>$product));
          */?>
        
      </div>
      <div class="vm-product-details-container">
  	   	<h5 class="item_name product_title">
		<?php
		//var_dump($product);
		$url = JRoute::_ (''.$product->link.$ItemidStr.''); 
			//print_r($url);
			?>
		<a href="<?php echo $url ?>"><?php echo shopFunctionsF::limitStringByWord($product->product_name,'45', '...'); ?></a> 
		</h5> 
		    <?php // Product Short Description
        	
          if (!empty($product->product_desc)) {
            ?>
            <p class="product_s_desc">
              <?php echo shopFunctionsF::limitStringByWord (JFilterOutput::cleanText($product->product_desc), 90, '...') ?>
            </p>
        <?php }  ?>
        <div class="clearfix"></div>
        <div class="vm3pr">
          <?php echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); 
          ?>
        </div>
      	<div class="btn-position">
  		<?php 
    	?>
    	<div class="box-optian">
    		<?php
    			$addcart = array();
    			foreach ($product->customfieldsSorted as $key => $value) {
					$addcart[] = $key;
    				//variants
    			}
    			//var_dump($addcart);
    			if (in_array('variants', $addcart, true) || in_array('addtocart', $addcart, true)) {
    				//echo '1';
					$class = "option";
    			}else {
    				$class = "empty";
    			}
    			
    		?>
		<div id="custom<?php echo $class; ?><?php echo $product->virtuemart_product_id ?>" class="custom<?php echo $class; ?>"> 
    	<?php 
    		echo shopFunctionsF::renderVmSubLayout('variants',array('product'=>$product,'row'=>0));
        	echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'row'=>0));
        ?>
		</div>
		<a data-fancybox="fancybox2"  id="fancy<?php echo $product->virtuemart_product_id ?>" href="#custom<?php echo $class; ?><?php echo $product->virtuemart_product_id ?>" class="addtocart-button btn slect-option <?php echo $class; ?>"><i class="fa fa-shopping-cart"></i><span><?php echo JText::_('COM_SELECTOPTION') ?></span></a>
		</div>
         <?php  if (is_file(JPATH_BASE . "/components/com_tmbox/template/mywishlists.tpl.php")) : ?>
                <div style="display:<?php echo $styleblockadd; ?>;"
                    class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . "/components/com_tmbox/template/mywishlists.tpl.php"); ?>
                </div>
                 <div style="display:<?php echo $styleblockremove; ?>;" class="wishlist remove list_wishlists_remove<?php echo $product->virtuemart_product_id; ?>">
                    <a class="remove_wishlist hasTooltips" title="<?php echo JText::_('REMOVE_TO_WHISHLIST'); ?>" onclick="TmboxRemoveWishlists('<?php echo $product->virtuemart_product_id; ?>');"><i class="fa fa-heart-o"></i><span> <?php echo JText::_("REMOVE_TO_WHISHLIST"); ?></span>   </a>
                </div>
            <?php endif; ?>
            <?php if (is_file(JPATH_BASE . "/components/com_tmbox/template/comparelist.tpl.php")) : ?>
                <div
                    class="compare list_compare<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . "/components/com_tmbox/template/comparelist.tpl.php"); ?>
                </div>
            <?php endif; ?>
    
    	</div>
    	<div class="clearfix"></div>
    	 <div class="vm-rating">
          <?php
          //print_r($showRating);
            echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$showRating, 'product'=>$product)); ?>
           
        </div>
       
      </div><div class="clearfix"></div>
      </div> 
    </div>
    	<?php 
		if ($prodslider == 'prodslider') {	
		 
			$nb ++;

		// Do we need to close the current row now?
		if ($col == $products_per_row || $nb>$BrowseTotalProducts) { ?>
			</div>
			<?php
			$col = 1;
			$row++;
		} else {
			$col ++;
		}
		 } else { 		
			$nb ++;

		// Do we need to close the current row now?
		if ($col == $products_per_row || $nb>$BrowseTotalProducts) { ?>
			</div>
			<?php
			$col = 1;
			$row++;
		} else {
			$col ++;
		}
			}  
	} 

	if(!empty($tabshome)){ 
		echo '</div>';	
	}
	?>

<?php 

} 
	if ($tabshome == 'tabshome'){ 
		echo '</div></div><div class="seeall"><a href="'.JRoute::_ ("index.php?option=com_virtuemart&view=category&virtuemart_category_id=1").'" class="btn">See all products</a></div>';
	}
	if ($prodslider == 'prodslider') {	
		echo '</div>';
	}
	vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack',false);
	vmJsApi::css('jquery.fancybox-1.3.4');
	echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$products)); 
?>


<script>
jQuery(document).ready(function() {
	jQuery(".slect-option").fancybox({
		maxWidth	: 400,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		wrapCSS : 'option-class',
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	jQuery( document ).on( "mousedown", ".option-class.fancybox-opened .addtocart-button.btn", function() {
 		setTimeout(function() { jQuery('.fancybox-close').trigger('click') }, 500);
	});
});

jQuery(document).ready(function () {
	jQuery('.comvirtuemartmod .prod-box ul.variants a.selected').each(function(){
		input_ids = jQuery(this).parents('ul').attr('data-type');
		data_title = jQuery(this).parents('ul').find('a.selected').data('titleVariants');
		jQuery(this).parents('ul').find('input[id="'+input_ids+'"]').val(data_title);
		//alert(data_title);
	})
	jQuery(document).on('click', '.comvirtuemartmod .prod-box ul.variants a', function(e){
		e.preventDefault();
		var btn = jQuery(this);
		input_id = jQuery(this).parents('ul').attr('data-type');
		jQuery(this).parents('ul').find('a').each(function(){jQuery(this).removeClass('selected')});
		jQuery(this).parents('ul').find('input[id="'+input_id+'"]').val(jQuery(this).attr('data-title-variants'));
		jQuery(this).addClass('selected');
		var data_title_variants = [];
		jQuery(this).parents('.variants-area').find('ul').each(function(){
			input_ids = jQuery(this).attr('data-type');
			data_title_variants.push(jQuery(this).find('input[id="'+input_ids+'"]').val());	
			
		})
		
		var maxH = btn.parents('.prod-box').find('.vm-product-media-container').height();

		//alert (maxH);
		var custom_id = jQuery(this).data('customId');
		var prod_id = jQuery(this).data('id');
		var prodoption = prod_id+','+custom_id+','+data_title_variants;

		jQuery.ajax({
			url: '?option=com_virtuemart&task=ap&view=productdetails&virtuemart_product_id='+prod_id,
			type: 'post',
			data: 'prodoption='+prodoption,
			dataType: 'json',
			beforeSend: function(){
	            },
			success: function(json){
				alert(json.prodid);
				if(json.prodid.length !== 0){
					//alert(json.prodid.length);
					element = btn.parents('.prod-box').find('.variants-area');
					var element = btn.parents('.prod-box').find('.addtocart-area input[name="virtuemart_product_id[]"]').val(json.prodid);
					var imagesHeight = btn.parents('.prod-box').find('.vm-product-media-container').css("height", maxH);
					var images = btn.parents('.prod-box').find('.vm-product-media-container a').remove();
					var vmrating = btn.parents('.prod-box').find('.vm-rating').html('');
					var title = btn.parents('.prod-box').find('h5.product_title a').remove();
					var product_s_des = btn.parents('.prod-box').find('.product_s_desc').html('');
					var price = btn.parents('.prod-box').find('.vm3pr > div').remove();

					btn.parents('.prod-box').find('.vm-product-media-container').html(json.images);
					btn.parents('.prod-box').find('.vm-rating').html(json.ratings);
					btn.parents('.prod-box').find('h5.product_title').html(json.title);
					btn.parents('.prod-box').find('.product_s_desc').html(json.product_s_desc);
					btn.parents('.prod-box').find('.vm3pr').html(json.price);
					//btn.parents('.prod-box').find('.addtocart-bar .notify ').addClass('addtocart-button');
					if(json.addcart == 'notify'){
						//alert('notify');
						btn.parents('.prod-box').find('.addtocart-bar .hiddennotify').addClass('displayblock');
						btn.parents('.prod-box').find('.addtocart-bar .addtocart-button').addClass('displaynone');
						btn.parents('.prod-box').find('.addtocart-bar .notify.btn').removeClass('displaynone');
					}else {
						//alert('cart');
						btn.parents('.prod-box').find('.addtocart-bar .hiddenaddcart').addClass('displayblock');
						btn.parents('.prod-box').find('.addtocart-bar .notify.btn').addClass('displaynone');
						btn.parents('.prod-box').find('.addtocart-bar .addtocart-button').removeClass('displaynone');
					}
				} else {
					alert('empty element');
					btn.parents('.prod-box').find('.addtocart-bar .hiddennotify').addClass('displayblock');
					btn.parents('.prod-box').find('.addtocart-bar .addtocart-button').addClass('displaynone');
					btn.parents('.prod-box').find('.addtocart-bar .notify.btn').removeClass('displaynone');
				}

				//alert (price);
			}
		}); 
		
	});
	});
</script>