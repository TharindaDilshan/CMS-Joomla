<?php // no direct access
defined('_JEXEC') or die('Restricted access');
vmJsApi::jPrice();

?>
<div class="vmgroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">

<?php if ($headerText) { ?>
	<div class="vmheader"><?php echo $headerText ?></div>
<?php } ?>

<div class="vmproduct vmproduct_<?php echo $params->get('moduleclass_sfx'); ?> sidebarlist">
<?php foreach ($products as $product) { ?>
	<div class="rows"><div class="spacer">
<?php
 if (!empty($product->images[0]) )
 $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" ',false) ;
 else $image = '';
   echo '<div class="vm-product-media-container">';	

 echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,array('title' => $product->product_name) );
 echo '</div>';?>
    
  <div class="vm-rating">
    <?php
    $ratingModel = VmModel::getModel('ratings');
    $showRating = $ratingModel->showRating();
   // print_r($rating);
      echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$showRating, 'product'=>$product)); ?>
     
  </div>	
<?php // Product Short Description
  if (!empty($product->product_s_desc)) {
    ?>
    <p class="product_s_desc">
      <?php echo shopFunctionsF::limitStringByWord (JFilterOutput::cleanText($product->product_s_desc), 30, '...') ?>
    </p>
<?php } 
// $product->prices is not set when show_prices in config is unchecked
if ($show_price and  isset($product->prices)) { ?>
<div class="vm3pr">
<?php
   echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); 
?>
</div>
 <?php } ?>
 <h5 class="item_name product_title">
      <?php $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
        $product->virtuemart_category_id); ?>

      <a href="<?php echo $url ?>"><?php echo shopFunctionsF::limitStringByWord($product->product_name,'40', '...'); ?></a> 
      </h5> 
<?php
 if ($show_addtocart) //echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product));
 ?>
 </div></div>

	<?php } ?>
<?php if ($footerText) { ?>
	<div class="vmheader"><?php echo $footerText ?></div>
<?php } ?>
</div>
</div>