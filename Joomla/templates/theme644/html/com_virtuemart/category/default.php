<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8715 2015-02-17 08:45:23Z Milbo $
 */

defined ('_JEXEC') or die('Restricted access');
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('params');
$query->from($db->quoteName('#__extensions'));
$query->where($db->quoteName('name')." = ".$db->quote('com_content'));
$db->setQuery($query);
$params = $db->loadResult();
$params = json_decode($params);

////////////////

function checkFilterOrderCustom($toCheck){

		if(empty($toCheck)) return $this->_selectedOrdering;

		if(!in_array($toCheck, $this->_validOrderingFieldName)){

			$break = false;
			vmSetStartTime();
			foreach($this->_validOrderingFieldName as $name){
				if(!empty($name) and strpos($name,$toCheck)!==FALSE){
					$this->_selectedOrdering = $name;
					$break = true;
					break;
				}
			}
			if(!$break){
				$app = JFactory::getApplication();
				$view = vRequest::getCmd('view');
				if (empty($view)) $view = 'virtuemart';
				$app->setUserState( 'com_virtuemart.'.$view.'.filter_order',$this->_selectedOrdering);
			}
		} else {
			$this->_selectedOrdering = $toCheck;
		}

		return $this->_selectedOrdering;
	}

	function getOrderByListCustom ($virtuemart_category_id = FALSE) {

		$getArray = vRequest::getGet();
		ksort($getArray);

		$fieldLink = '';

		foreach ($getArray as $key => $value) {
			if (is_array ($value)) {
				foreach ($value as $k => $v) {
					if(empty($v)) continue;
					$fieldLink .= '&' . urlencode($key) . '[' . urlencode($k) . ']' . '=' . urlencode($v);
				}
			}
			else {
				if($key=='dir' or $key=='orderby') continue;
				if(empty($value)) continue;
				$fieldLink .= '&' . urlencode($key) . '=' . urlencode($value);
			}
		}

		$fieldLink = 'index.php?'. ltrim ($fieldLink,'&');

		$orderDirLink = '';
		$orderDirConf = VmConfig::get ('prd_brws_orderby_dir');
		$orderDir = vRequest::getCmd ('dir', $orderDirConf);
		if ($orderDir != $orderDirConf ) {
			$orderDirLink .= '&dir=' . $orderDir;	//was '&order='
		}

		$orderbyTxt = '';
		$orderby = vRequest::getString ('orderby', VmConfig::get ('browse_orderby_field'));

		$orderbyCfg = VmConfig::get ('browse_orderby_field');
		if ($orderby != $orderbyCfg) {
			$orderbyTxt = '&orderby=' . $orderby;
		}

		$manufacturerTxt = '';
		$manufacturerLink = '';
		if (VmConfig::get ('show_manufacturers')) {

			$manuM = VmModel::getModel('manufacturer');
			vmSetStartTime('mcaching');
			$mlang=(!VmConfig::get('prodOnlyWLang',false) and VmConfig::$defaultLang!=VmConfig::$vmlang and Vmconfig::$langCount>1);
			if(true){
				$cache = JFactory::getCache('com_virtuemart_cat_manus','callback');
				$cache->setCaching(true);
				$manufacturers = $cache->call( array( 'VirtueMartModelManufacturer', 'getManufacturersOfProductsInCategory' ),$virtuemart_category_id,VmConfig::$vmlang,$mlang);
				vmTime('Manufacturers by Cache','mcaching');
			} else {
				$manufacturers = $manuM ->getManufacturersOfProductsInCategory($virtuemart_category_id,VmConfig::$vmlang,$mlang);
				vmTime('Manufacturers by function','mcaching');
			}

			// manufacturer link list
			$manufacturerLink = '';
			$virtuemart_manufacturer_id = vRequest::getInt ('virtuemart_manufacturer_id', '');
			if ($virtuemart_manufacturer_id != '') {
				$manufacturerTxt = '&virtuemart_manufacturer_id=' . $virtuemart_manufacturer_id;
			}

			if (count ($manufacturers) > 0) {
				$manufacturerLink = '<div class="orderlist">';
				if ($virtuemart_manufacturer_id > 0) {
					$allLink = str_replace($manufacturerTxt,'',$fieldLink);
					$manufacturerLink .= '<div><a href="' . JRoute::_ ($allLink . $orderbyTxt . $orderDirLink , FALSE) . '">' . vmText::_ ('COM_VIRTUEMART_SEARCH_SELECT_ALL_MANUFACTURER') . '</a></div>';
				}
				if (count ($manufacturers) > 1) {
					foreach ($manufacturers as $mf) {
						$link = JRoute::_ ($fieldLink . '&virtuemart_manufacturer_id=' . $mf->virtuemart_manufacturer_id . $orderbyTxt . $orderDirLink,FALSE);
						if ($mf->virtuemart_manufacturer_id != $virtuemart_manufacturer_id) {
							$manufacturerLink .= '<div><a title="' . $mf->mf_name . '" href="' . $link . '">' . $mf->mf_name . '</a></div>';
						}
						else {
							$currentManufacturerLink = '<div class="title">' . vmText::_ ('COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL') . '</div><div class="sort_list"><div class="activeOrder">' . $mf->mf_name . '</div>';
						}
					}
				}
				elseif ($virtuemart_manufacturer_id > 0) {
					$currentManufacturerLink = '<div class="title">' . vmText::_ ('COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL') . '</div><div class="sort_list"><div class="activeOrder">' . $manufacturers[0]->mf_name . '</div>';
				}
				else {
					$currentManufacturerLink = '<div class="title">' . vmText::_ ('COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL') . '</div><div class="sort_list"><div class="Order"> ' . $manufacturers[0]->mf_name . '</div>';
				}
				$manufacturerLink .= '</div>';
			}
		}

		/* order by link list*/
		$orderByLink = '';
		$fields = VmConfig::get ('browse_orderby_fields');
		if (count ($fields) > 1) {
			$orderByLink = '<div class="orderlist">';
			foreach ($fields as $field) {

					$dotps = strrpos ($field, '.');
					if ($dotps !== FALSE) {
						$prefix = substr ($field, 0, $dotps + 1);
						$fieldWithoutPrefix = substr ($field, $dotps + 1);
					}
					else {
						$prefix = '';
						$fieldWithoutPrefix = $field;
					}
				if ($fieldWithoutPrefix != $orderby) {

					$text = vmText::_ ('COM_VIRTUEMART_' . strtoupper ($fieldWithoutPrefix));

					$field = explode('.',$field);
					if(isset($field[1])){
						$field = $field[1];
					} else {
						$field = $field[0];
					}
					$link = JRoute::_ ($fieldLink . $manufacturerTxt . '&orderby=' . $field,FALSE);

					$orderByLink .= '<div><a title="' . $text . '" href="' . $link . '">' . $text . '</a></div>';
				}
			}
			$orderByLink .= '</div>';
		}


		if($orderDir == 'ASC'){
			$orderDir = 'DESC';
		} else {
			$orderDir = 'ASC';
		}

		if ($orderDir != $orderDirConf ) {
			$orderDirLink = '&dir=' . $orderDir;	//was '&order='
		} else {
			$orderDirLink = '';
		}

		$orderDirTxt = vmText::_ ('COM_VIRTUEMART_'.$orderDir);

		$link = JRoute::_ ($fieldLink . $orderbyTxt . $orderDirLink . $manufacturerTxt,FALSE);

		// full string list
		if ($orderby == '') {
			$orderby = $orderbyCfg;
		}
		$orderby = strtoupper ($orderby);


		$dotps = strrpos ($orderby, '.');
		if ($dotps !== FALSE) {
			$prefix = substr ($orderby, 0, $dotps + 1);
			$orderby = substr ($orderby, $dotps + 1);
		}
		else {
			$prefix = '';
		}

		$orderByList = '<div class="orderlistcontainer sorting"><div class="title">' . vmText::_ ('COM_VIRTUEMART_ORDERBY') . '</div><div class="sort_list"><div class="activeOrder"><a href="' . $link . '">' . vmText::_ ('COM_VIRTUEMART_SEARCH_ORDER_' . $orderby) . ' ' . $orderDirTxt . '</a></div>';
		$orderByList .= $orderByLink . '</div></div>';

		$manuList = '';
		if (VmConfig::get ('show_manufacturers')) {
			if (empty ($currentManufacturerLink)) {
				$currentManufacturerLink = '<div class="title">' . vmText::_ ('COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL') . '</div><div class="sort_list"><div class="activeOrder">' . vmText::_ ('COM_VIRTUEMART_SEARCH_SELECT_MANUFACTURER') . '</div>';
			}
			$manuList = '<div class="orderlistcontainer manufacturer">'.$currentManufacturerLink;
			$manuList .= $manufacturerLink . '</div></div>';

		}

		return array('orderby'=> $orderByList, 'manufacturer'=> $manuList);
	}

///////////////

$this->orderByListCustom = getOrderByListCustom($this->categoryId);
?>
<div id="com_virtuemart">
<div class="category-view"> <?php
$js = "
jQuery(document).ready(function () {
	jQuery('.listcontainer').hover(
		function() { jQuery(this).addClass('show')},
		function() { jQuery(this).removeClass('show')}
	)
});
";
vmJsApi::addJScript('vm.hover',$js);

if($this->category->category_name) : ?>
<h3><?php echo $this->category->category_name; ?></h3>
<?php endif;

if (empty($this->keyword) and !empty($this->category)) {
	?>
<div class="category_description">
	<?php echo $this->category->category_description; ?>
</div>
<?php
}

// Show child categories
if (VmConfig::get ('showCategory', 1) and empty($this->keyword)) {
	if (!empty($this->category->haschildren)) {

		echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=>$this->category->children));

	}
}
//print_r($this->products);
if($this->showproducts){

?>
<div class="browse-view comvirtuemartmod">
<?php

if (!empty($this->keyword)) {
	//id taken in the view.html.php could be modified
	$category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>
	<h3><?php echo $this->keyword; ?></h3>

	<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=category&limitstart=0', FALSE); ?>" method="get">

		<!--BEGIN Search Box -->
		<div class="virtuemart_search">
			<?php echo $this->searchcustom ?>
			<?php echo $this->searchCustomValues ?>
			<input name="keyword" class="inputbox" type="text" size="20" value="<?php echo $this->keyword ?>"/>
			<button type="submit" value="<?php echo vmText::_ ('COM_VIRTUEMART_SEARCH') ?>" class="btn button" onclick="this.form.keyword.focus();"><?php echo vmText::_ ('COM_VIRTUEMART_SEARCH') ?></button>
		</div>
		<input type="hidden" name="search" value="true"/>
		<input type="hidden" name="view" value="category"/>
		<input type="hidden" name="option" value="com_virtuemart"/>
		<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>

	</form>
	<!-- End Search Box -->
<?php  } ?>

<?php // Show child categories
if(!empty($this->products)){
	?>
<div class="orderby-displaynumber">
	<div class="vm-order-list">
		<?php echo $this->orderByListCustom['orderby']; ?>
		<?php echo $this->orderByListCustom['manufacturer']; ?>
	</div>
	<div class="display-number"><?php echo $this->vmPagination->getResultsCounter ();?><br/><?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?></div>
	<div class="clearfix"></div>
	<?php if($this->vmPagination->getPagesLinks ()): ?>
	<div class="pagination vm-pagination-top">
		<?php echo $this->vmPagination->getPagesLinks (); ?>
		<?php if($params->show_pagination_results): ?>
		<span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></span>
		<?php endif; ?>
	</div>
	<?php endif; ?>

</div> <!-- end of orderby-displaynumber -->

	<div class="grid-list">
		<div data-grid="grid" class="active"><i class="fa fa-th-large"></i></div>
		<div data-grid="list"><i class="fa fa-th-list"></i></div>
	</div>
	<?php
	if (!empty($this->products)) { ?>
	<div class="virtuemart-category__container grid">
	<?php $products = array();
	$products[0] = $this->products;
	echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

	?>
<?php if($this->vmPagination->getPagesLinks ()): ?>
<div class="pagination vm-pagination-bottom">
<?php echo $this->vmPagination->getPagesLinks (); ?>
<?php if($params->show_pagination_results): ?>
<span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></span>
<?php endif; ?>
</div>
<?php endif; ?>
</div>

	<?php
	}
} elseif (!empty($this->keyword)) {
	echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>

<?php } ?>
</div>


<!-- end browse-view -->
</div>

<script>
	function createCookie(a,b,c,d){if(c){var e=new Date();e.setTime(e.getTime()+(c*24*60*60*1000));var f="; expires="+e.toGMTString()}else var f="";if(d){var d="; path="+d}else var d="; path=/";document.cookie=a+"="+b+f+d}

	function readCookie(a){var b=a+"=";var d=document.cookie.split(';');for(var i=0;i<d.length;i++){var c=d[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(b)==0)return c.substring(b.length,c.length)}return null}

	jQuery(function($){
		if(readCookie('grid-list') == 'list'){
			$('.grid-list div').removeClass('active');
			$('.grid-list div[data-grid=list]').addClass('active');
			$('.virtuemart-category__container').removeClass('grid').addClass('list');
		}
		$('.grid-list div').click(function(){
			if(!$(this).hasClass('active')){
				$('.grid-list div').removeClass('active');
				$(this).addClass('active');
				data_grid = $(this).attr('data-grid');
				createCookie('grid-list',data_grid,365)
				$('.virtuemart-category__container').addClass('interim').delay(500)
				.queue(function (n) {
					$(this).removeClass('grid').removeClass('list').removeClass('interim').addClass(data_grid);
					n();
               	});
			}
		})
	})
</script>