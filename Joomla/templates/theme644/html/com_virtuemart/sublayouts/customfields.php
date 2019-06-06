<?php
/**
* sublayout products
*
* @package	VirtueMart
* @author Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
* @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
*/

defined('_JEXEC') or die('Restricted access');
$product = $viewData['product'];
$position = $viewData['position'];
$customTitle = isset($viewData['customTitle'])? $viewData['customTitle']: false;;
if(isset($viewData['class'])){
	$class = $viewData['class'];
} else {
	$class = 'product-fields';
}
if (!empty($product->customfieldsSorted[$position])) { 
$app = JFactory::getApplication();	
$doc = JFactory::getDocument();
$document =& $doc;
$template = $app->getTemplate();
$document->addScript('templates/'.$template.'/js/owl.carousel.min.js');
$doc->addStyleSheet('templates/'.$template.'/css/owl.carousel.css');
?>
<script>
		jQuery(document).ready(function(){

  			jQuery('.owl-carousel-related').owlCarousel(
  				{
				    loop:false,
				    margin:0,
				    autoHeight:false,
				    autoplay:true,
				    autoplayTimeout:12000,
				    autoplayHoverPause:true,
				    //mouseDrag:false,
				    nav:true,
				    dots:false,
				    navRewind:false,
				    dotsEach:true,
				    navText: [ '<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>' ],
				    responsive:{
			    	    0:{
				            items:1
				        },
				        480:{
				            items:2
				        },
				        768:{
				            items:3
				        },
				        1200:{
				            items:4
				        }
				    }
				}

  			);
  			var K = jQuery('.owl-carousel-related .itemslide').length;
  			if (K < 5) {
				jQuery('.owl-carousel').addClass('lengs');	
  			}

		});
		</script>
	<div class="<?php echo $class?>">
		<?php if($position === 'related_products'){
			if($customTitle and isset($product->customfieldsSorted[$position][0])){
				$field = $product->customfieldsSorted[$position][0]; ?>
			<h4 class="heading-style-4"><?php echo vmText::_ ($field->custom_title) ?></h4>
		<?php } ?>
		<div class="row-slide">
		<div class="owl-carousel-related owl-theme productdetails">
		
			<?php $custom_title = null;
			foreach ($product->customfieldsSorted[$position] as $field) {
				 ?>
				<div class='itemslide'>
					<?php if (!$customTitle and $field->custom_title != $custom_title and $field->show_title and $position != 'video' and $position != 'additional') { ?>
						<span class="product-fields-title-wrapper"><span class="product-fields-title"><strong><?php echo vmText::_ ($field->custom_title) ?></strong></span>
							<?php if ($field->custom_tip) {
								echo JHtml::tooltip (vmText::_($field->custom_tip), vmText::_ ($field->custom_title), 'tooltip.png');
							} ?></span>
					<?php }
					if (!empty($field->display)){
						?><div class="product product-field-display"><?php echo $field->display ?></div><?php
					}
					if (!empty($field->custom_desc)){
						?><div class="product-field-desc"><?php echo vmText::_($field->custom_desc) ?></div> <?php
					}
					?>
				</div>
			
			<?php
				$custom_title = $field->custom_title;
			} ?>
			</div>
			</div>
		<?php } else {
			if($customTitle and isset($product->customfieldsSorted[$position][0])){
				$field = $product->customfieldsSorted[$position][0]; ?>
			<div class="product-fields-title-wrapper"><span class="product-fields-title"><strong><?php echo vmText::_ ($field->custom_title) ?></strong></span>
				<?php if ($field->custom_tip) {
					echo JHtml::tooltip (vmText::_($field->custom_tip), vmText::_ ($field->custom_title), 'tooltip.png');
				} ?>
			</div> <?php
			}
			$custom_title = null;
			foreach ($product->customfieldsSorted[$position] as $field) {
			if ( $field->is_hidden || empty($field->display)) continue; //OSP http://forum.virtuemart.net/index.php?topic=99320.0
			?><div class="product-field product-field-type-<?php echo $field->field_type ?>">
				<?php if (!$customTitle and $field->custom_title != $custom_title and $field->show_title) { ?>
					<span class="product-fields-title-wrapper"><span class="product-fields-title"><strong><?php echo vmText::_ ($field->custom_title) ?></strong></span>
						<?php if ($field->custom_tip) {
							echo JHtml::tooltip (vmText::_($field->custom_tip), vmText::_ ($field->custom_title), 'tooltip.png');
						} ?></span>
				<?php }
				if (!empty($field->display)){
					?><div class="product-field-display"><?php echo $field->display ?></div><?php
				}
				if (!empty($field->custom_desc)){
					?><div class="product-field-desc"><?php echo vmText::_($field->custom_desc) ?></div> <?php
				}
				?>
			</div>
		<?php
			$custom_title = $field->custom_title;
		} 
		} ?>
	
	</div>
<?php } ?>