<?php
/**
*
* Layout for the add to cart popup
*
* @package	VirtueMart
* @subpackage Cart
* @author Max Milbers
*
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2013 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
 ?>
<div id="com_virtuemart">
	<div class="row-container">
		<div class="container-fluid">
		<?php if($this->products){
			vmJsApi::addJScript("/modules/mod_virtuemart_cart_tm/assets/js/update_cart.js",false,false);
			$plg=new plgSystemVM3_Cart(JDispatcher::getInstance(),array());
			$data=$plg->prepareAjaxData2();
			VmConfig::loadConfig();
			if(!class_exists('VirtueMartCart')) require(VMPATH_SITE.DS.'helpers'.DS.'cart.php');
			foreach($this->products as $product){
				//var_dump($product);
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select($db->quoteName('file_url'))
				->from($db->quoteName('#__virtuemart_medias'))
				->where($db->quoteName('virtuemart_media_id') . ' = '. $db->quote($product->virtuemart_media_id[0]));
				$db->setQuery($query);
				$result = $db->loadResult();
				if($product->quantity>0){ 
					$i = $product->virtuemart_product_id;
					foreach ($data->products as $prod){ 
						if($prod["virtuemart_product_id"] == $i ){
					?>
					<div class="row-box-cart">
						<div class="row-box-width50">
							<?php 
								echo '<div class="title-left"><i class="fa fa-check-square-o"></i>'.vmText::sprintf('TPL_PRODUCT_ADDED').'</div>'; 
								echo '<div class="product_img"><img src="'.JURI::base(true).'/'.$result.'" alt=""></div>';
								echo '<h5 class="heading-style-5 vm_product_title">'.$product->product_name.'</h5>';
					          	if (!empty($product->product_s_desc)) {
						            ?>
						            <p class="product_s_desc">
						              <?php echo shopFunctionsF::limitStringByWord (JFilterOutput::cleanText($product->product_s_desc), 50, '...') ?>
						            </p>
						        <?php } 
								if(!empty($prod["product_attributes"])) {
								?>
								<div class="product_attributes"><?php echo $prod["product_attributes"]; ?></div>
								<span class="quantity"><?php echo $prod["quantity"]; ?></span><div class="prices" style="display:inline;"><?php echo $prod["prices"]; ?></div>
								<?php
							}
							?>

						</div>
						<div class="row-box-width50 color">
							<?php echo '<div class="title-right">'.Jtext::_('TPL_PRODUCT_ADDED_R').' '.$data->totalProduct.' '.Jtext::_('TPL_PRODUCT_ADDED_RT').'</div>'; ?>
							<span class="totalTextProduct"><?php echo JText::_('TPL_VIRTUEMART_CART_TOTAL_PRODUCTs'); ?></span>
							<div class="totalProduct">
							<?php echo $prod["subtotal"]; ?>
							</div>
							<div class="clearfix"></div>
							<span class="totalTexts"><?php echo JText::_('TPL_VIRTUEMART_CART_TOTAL'); ?></span>
							<div class="total">
							<?php
								
								 if ($data->totalProduct) echo  $data->billTotal; ?>
							</div>
							<div class="clearfix"></div>
							<a class="btn close_facebox" href="#" ><span><?php if($doc->direction != 'rtl') { ?><i class="fa fa-chevron-left"></i> <?php } else { ?><i class="fa fa-chevron-right"></i> <?php } ?><?php echo vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING'); ?></span></a>
							<a class="btn fright" href="<?php echo $this->cart_link; ?>"><span><?php echo vmText::_('COM_VIRTUEMART_CART_SHOW'); ?><?php if($doc->direction == 'rtl') { ?> <i class="fa fa-chevron-left"></i><?php } else { ?> <i class="fa fa-chevron-right"></i><?php } ?></span></a>
						</div>
					</div>	
						<?php
						}
					}
				} else {
					if(!empty($product->errorMsg)){ ?>
						<div class="row-box-cart">
						<div class="row-box-width100">
						<?php echo '<div class="title-out">'.$product->errorMsg.'</div>'; ?>
						</div>
						</div>
					<?php }
				}


			}
			
		} ?>
		
		<?php if(VmConfig::get('popup_rel',1) && $this->products and is_array($this->products) && count($this->products)>0){
			$app = JFactory::getApplication();	
			$doc = JFactory::getDocument();
			$document =& $doc;
			$template = $app->getTemplate();?>
			<?php
			vmJsApi::addJScript("/templates/'.$template.'/js/owl.carousel.min.js",false,false);
			$product = reset($this->products);
			$customFieldsModel = VmModel::getModel('customfields');
			$product->customfields = $customFieldsModel->getCustomEmbeddedProductCustomFields($product->allIds,'R');
			$customFieldsModel->displayProductCustomfieldFE($product,$product->customfields);
			//$relatedempty = array();
			
			if(!empty($product->customfields)){
			foreach($product->customfields as $relatedfields){ 
				//print_r($relatedfields->field_type);
				if($relatedfields->field_type == 'R'){
					$relatedempty = 'R';
				}else {
					$relatedempty = 'Y';
				}
			}		
			if($relatedempty !== 'Y'){
			 ?>
				<div class="product-related-products">
					<h4 class="heading-style-4"><?php echo vmText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></h4>
					<div class="cols-12">
						<div class="row-slide">
		<div class="owl-carousel-relateds owl-theme productdetails">
					<?php $i = 0;

					foreach($product->customfields as $rFields){

						if($rFields->field_type == 'R'){
							//$relatedempty[] = $rFields->field_type;
							//print_r($relatedempty);
						//if($i > 2) continue;
						if(!empty($rFields->display)){ ?>
						<div class='itemslide'>
							<div class="product-field-display"><?php echo $rFields->display ?></div>
						</div>
					<?php $i++;
						} }
					} ?>
					</div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>
<script>
	jQuery(function(){
		jQuery('.vm-customfield-mod .product-field-type-C span').each(function(){
			jQuery(this).text(jQuery(this).text().split('/')[0]);
		})
		jQuery('#facebox').prepend('<div id="facebox_overlay_inner"/>');
		jQuery('.close_facebox, #facebox_overlay_inner').click(function(){
			jQuery(document).trigger('close.facebox');
			return false;
		})
	})
</script>
<script>
		jQuery(document).ready(function(){
  			jQuery('.owl-carousel-relateds').owlCarousel(
  				{
				    loop:true,
				    margin:0,
				    autoHeight:false,
				    autoplay:true,
				    autoplayTimeout:12000,
				    autoplayHoverPause:true,
				    nav:true,
				    dots:false,
				    navRewind:false,
				    navText: [ '<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>' ],
				    responsive:{
			    	    0:{
				            items:2
				        },
				        480:{
				            items:3
				        },
				        768:{
				            items:4
				        },
				        1200:{
				            items:4
				        }
				    }
				}

  				);
		});
		</script>