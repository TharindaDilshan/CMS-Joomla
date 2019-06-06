<?php defined('_JEXEC') or die('Restricted access');
$related = $viewData['related'];
//var_dump($related);
$customfield = $viewData['customfield']; ?>
		<div class="prod-box">
			<div class="vm-product-media-container">
			<?php echo JHtml::link (JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $related->virtuemart_product_id . '&virtuemart_category_id=' . $related->virtuemart_category_id),$viewData['thumb']); ?>
			</div>
			<?php //juri::root() For whatever reason, we used this here, maybe it was for the mails
			$app = JFactory::getApplication();
			$view = $app->input->getCmd('view', '');
			if($customfield->wPrice){
			$currency = calculationHelper::getInstance()->_currencyDisplay; ?>
			<div class="vm3pr">
			  <?php echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$related,'currency'=>$currency)); ?>
			</div>
			<?php }
			if($view !=='cart'){
			echo '<h5 class="heading-style-5">'.JHtml::link (JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $related->virtuemart_product_id . '&virtuemart_category_id=' . $related->virtuemart_category_id), $related->product_name, array('target'=>'_blank')).'</h5>';
			} else {
				echo '<h6 class="heading-style-6">'.JHtml::link (JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $related->virtuemart_product_id . '&virtuemart_category_id=' . $related->virtuemart_category_id), $related->product_name, array('target'=>'_blank')).'</h6>';
			} ?>
			<div class="vm-product-details-container">
			<?php // Product Short Description
			  if (!empty($related->product_s_desc) && $view !=='cart') { ?>
			    <p class="product_s_desc">
			      <?php echo shopFunctionsF::limitStringByWord (JFilterOutput::cleanText($related->product_s_desc), 120, '...') ?>
			    </p>
			<?php }
		
			echo shopFunctionsF::renderVmSubLayout('variants',array('product'=>$related,'row'=>0));
			echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$related,'row'=>0)); ?>
			</div>
		</div>
	
