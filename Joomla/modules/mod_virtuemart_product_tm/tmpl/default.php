<?php // no direct access
defined ('_JEXEC') or die('Restricted access');
// add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
vmJsApi::jPrice();


$col = 1;
?>
<div class="comvirtuemartmod vmgroup<?php echo $params->get ('moduleclass_sfx') ?>">
	<?php if ($headerText) { ?>
	<div class="vmheader"><?php echo $headerText ?></div>
	<?php
}
	if ($display_style == "div") {
		?>
		<div class="vmproduct<?php echo $params->get ('moduleclass_sfx'); ?> productdetails">
		
			<?php
				if (!empty($products)) { 
				if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
				$currency = CurrencyDisplay::getInstance( );
			 	$ratingModel = VmModel::getModel('ratings');
			    $rating = $productModel->withRating;
			    $showRating = $rating;
				$productsLayout = VmConfig::get ( 'productsublayout', 'products' );
				$prod = array();
				$prod[0] = $products;
				echo shopFunctionsF::renderVmSubLayout($productsLayout,array('products'=>$prod,'currency'=>$currency,'products_per_row'=>$products_per_row,'showRating'=>$showRating));
			}?>
		</div>
		<?php
	} else {
		$last = count ($products) - 1;
		?><div class="row-slide">
		<div class="owl-carousel<?php echo $params->get ('moduleclass_sfx'); ?> owl-theme vmproduct<?php echo $params->get ('moduleclass_sfx'); ?> productdetails">
			<?php
				$app 	  = JFactory::getApplication();	
				$doc = JFactory::getDocument();
				$document =& $doc;
				$template = $app->getTemplate();
				$document->addScript('templates/'.$template.'/js/owl.carousel.min.js');
				$doc->addStyleSheet('templates/'.$template.'/css/owl.carousel.css');
				
				if (!empty($products)) { 
				if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
				$prodslider = 'prodslider';
				$currency = CurrencyDisplay::getInstance( );
			 	$ratingModel = VmModel::getModel('ratings');
			    $rating = $productModel->withRating;
			    $showRating = $rating;
				$productsLayout = VmConfig::get ( 'productsublayout', 'products' );
				$prod = array();
				$prod[0] = $products;
				echo shopFunctionsF::renderVmSubLayout($productsLayout,array('products'=>$prod,'currency'=>$currency,'products_per_row'=>$products_per_row,'showRating'=>$showRating ,'prodslider'=>$prodslider));
			}?>
		</div>
		</div>
		<script>
		jQuery(document).ready(function(){
  			jQuery('.owl-carousel<?php echo $params->get ('moduleclass_sfx'); ?>').owlCarousel(
  				{
				    loop:<?php echo $loop; ?>,
				    margin:0,
				    autoHeight:false,
				    autoplay:<?php echo $autoplay; ?>,
				    autoplayTimeout:<?php echo $autoplayTimeout; ?>,
				    navText: [ '<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>' ],
				    autoplayHoverPause:true,
				    nav:<?php echo $nav; ?>,
				    dots:false,
				    navRewind:false,
				    responsive:{
			    	    0:{
				            items:<?php echo $responsPhone2; ?>
				        },
				        480:{
				            items:<?php echo $responsPhone; ?>
				        },
				        768:{
				            items:<?php echo $responsTablet; ?>
				        },
				        1200:{
				            items:<?php echo $responsDesktop; ?>
				        }
				    }
				}

  				);
		});
		</script>
		<?php
	}
	if ($footerText) : ?>
		<div class="vmfooter<?php echo $params->get ('moduleclass_sfx') ?>">
			<?php echo $footerText ?>
		</div>
		<?php endif; ?>
</div>