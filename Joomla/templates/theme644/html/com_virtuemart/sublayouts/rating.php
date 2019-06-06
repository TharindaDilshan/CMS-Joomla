<?php defined('_JEXEC') or die('Restricted access');

$product = $viewData['product'];

if ($viewData['showRating']) {
	$maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
	if (!empty($product->rating)) {
		$ratingwidth = $product->rating * 15;
  ?>
	<div class="ratingbox">
		<span class="rating-icons">
        <?php 
        for ($i = 1; $i <= 5 ; $i ++ ) { 		                            	
        	if ($i <= $product->rating) {
        		 echo '<i class="fa fa-star"></i> ';
                } else {
                    echo '<i class="fa fa-star-o"></i> ';
        	}                     	
        } ?>
        </span>
      
        <div class="clearfix"></div>
    </div>
	<?php
	} else { ?>
		<div class="ratingbox">
		<span class="rating-icons">
        <?php 
        for ($i = 1; $i <= 5 ; $i ++ ) { 		                            	
        	if ($i <= $product->rating) {
        		 echo '<i class="fa fa-star"></i> ';
                } else {
                    echo '<i class="fa fa-star-o"></i> ';
        	}                     	
        } ?>
        </span>
      
        <div class="clearfix"></div>
    </div>
	<?php }
}