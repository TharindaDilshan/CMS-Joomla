<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filter.filteroutput' );
$product = $viewData['product'];

$html = '';

if(isset($viewData['rowHeights'])){
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = TRUE;
}
$position = 'addtocart';
$product_model = VmModel::getModel('product');
$virtuemart_product_id = $product->virtuemart_product_id;
$quantityArray = vRequest::getInt ('quantity', array()); //is sanitized then

$quantity = 1;
if (!empty($quantityArray[0])) {
	$quantity = $quantityArray[0];
}
$product_extended = $product_model->getProduct($virtuemart_product_id,TRUE,TRUE,TRUE,$quantity);
$dimensions = array('product_length','product_width','product_height','product_weight'); 
if(isset($product_extended->customfields) && $product_extended->customfields && isset($product_extended->customfields[0]->options)){
	$active = $product_extended->customfields[0]->options->$virtuemart_product_id;
	$html = '';
	$dropdowns = array();
	if(isset($product_extended->customfields[0]->options->{$product->virtuemart_product_id})){
		$productSelection = $product_extended->customfields[0]->options->{$product->virtuemart_product_id};
	} else {
		$productSelection = false;
	}
	$virtuemart_custom_id = $product_extended->customfields[0]->virtuemart_custom_id;
	foreach($product_extended->customfields[0]->options as $product_id => $variants){

		foreach ($variants as $k => $variant){
			if($variant!='0'){

				if(!isset($dropdowns[0][$k]) or !is_array($dropdowns[0][$k])) $dropdowns[0][$k] = array();
				if(!in_array($variant,$dropdowns[0][$k])){
					$dropdowns[0][$k][] = $variant;
					if($productSelection[$k] == $variant){
						$dropdowns[1][$k][] = 'selected';
					}
					else{
						$dropdowns[1][$k][] = '';
					}
					$soption = $product_extended->customfields[0]->selectoptions[$k];
					$soption->sclass = '';
					if($soption->clabel!=''){
						$soption->sclass = $soption->clabel;
					}
					else {
						$soption->sclass = $soption->voption;
					}
					$dropdowns[2][$k] = JFilterOutput::stringURLSafe($soption->sclass);
					if($product_extended->customfields[0]->showlabels){
						$soption->slabel = false;
						if( in_array($soption->voption,$dimensions) ){
							$soption->slabel = vmText::_('COM_VIRTUEMART_'.strtoupper($soption->voption));
						} else if(!empty($soption->clabel)){
							$soption->slabel = vmText::_($soption->clabel);
						}
						$dropdowns[3][$k] = $soption->slabel;
					}
				}
			}
		}
	}
	$ids = array();
	foreach($dropdowns as $key => $dropdown){
		ksort($dropdowns[$key]);
	}
	$Itemid = vRequest::getInt('Itemid','');
	if(!empty($Itemid)){
		$Itemid = '&Itemid='.$Itemid;
	}
	$session = JFactory::getSession ();
	$virtuemart_category_id = $session->get ('vmlastvisitedcategoryid', 0, 'vm');

	$html ='';

	foreach($dropdowns[0] as $k => $elem_1){
		$html_1 ='';
		$html_2 ='';


		foreach($elem_1 as $i => $elem){

			$color = false;
			$elem_origin = $elem;
			//var_dump($elem_origin);
			if(strpos($elem, '/#')){
				$elem = explode('/',$elem);
				$color = $elem[1];
				$elem = $elem[0];
			}

			$text = vmText::_($elem);
			foreach($product_extended->customfields[0]->options as $product_id => $variants){
				if($variants[$k]==$dropdowns[0][$k][$i]) {
					$dropdowns[4][$k][$i][] = $product_id;
				}
			}
			$id = $dropdowns[4][$k][$i][0];
			foreach($dropdowns[0] as $key => $drop_level_1){
				if ($key != $k) {
					$z = array_search($productSelection[$key], $drop_level_1);
					if ($key !== false) {
						$el = array();
						$el[$k] = $elem_origin;
						$el[$key] = $productSelection[$key];
						ksort($el);
						foreach($product_extended->customfields[0]->options as $product_id => $variants){
							if($variants == $el){
								$id = $product_id;
							}
						}
					}
				}
			}
			if('category' == vRequest::getCmd('view')){
				$url = '';
				$url = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $virtuemart_category_id );
			}else {
				$url = '';
				$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $virtuemart_category_id . '&virtuemart_product_id='.$id.$Itemid);
			}
			if($color){
				$html_1 .= '<li><a data-custom-id="'.$virtuemart_custom_id.'" data-id="'.$virtuemart_product_id.'" class="hasTooltip '.$dropdowns[1][$k][$i].'" href="'.$url.'" title="'.$text.'" data-title-variants="'.$elem_origin.'" style="background-color: '.$color.'" data-dynamic-update="1">&nbsp;</a></li>';
			}
			else{
				$html_1 .= '<li><a data-custom-id="'.$virtuemart_custom_id.'" data-title-variants="'.$elem_origin.'" data-id="'.$virtuemart_product_id.'" class="'.$dropdowns[1][$k][$i].'" href="'.$url.'" data-dynamic-update="1">'.$text.'</a></li>
					';
			}
		}

		if($html_1 !=''){
			$html_2 = '<ul data-type="variants_'.$dropdowns[2][$k].'" class="variants variants_'.$dropdowns[2][$k].'">'.$html_1.'<input id="variants_'.$dropdowns[2][$k].'"  type="hidden" name="variants_'.$dropdowns[2][$k].'" value=""/></ul>';
			
			if($dropdowns[3][$k]){
				$html_2 = '<div class="variant_title">'.$dropdowns[3][$k].' <span class="required">*</span></div>'.$html_2;
			}
			$html .= $html_2;
		}

		$attribs = array('class'=>'hasTooltip','data-dynamic-update'=>'1');
		if('productdetails' != vRequest::getCmd('view')){
			$attribs['reload'] = '1';
		}
	}
}
?>
<div class="variants-area">
	<?php if (!VmConfig::get('use_as_catalog', 0)  ) {
		echo $html;
	} ?>
</div>