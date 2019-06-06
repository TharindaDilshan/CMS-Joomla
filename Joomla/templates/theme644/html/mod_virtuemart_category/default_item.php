<?php // no direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php foreach ($categories as $category) {
	$active_menu = '';
	$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
	$cattext = $category->category_name;
	if (in_array( $category->virtuemart_category_id, $parentCategories)) $active_menu = 'current';
	$categoryModel = VmModel::getModel('Category');
	$category->childs = $categoryModel->getChildCategoryList($vendorId, $category->virtuemart_category_id) ;
	?>

	<dt class="<?php echo $active_menu; if ($category->childs) echo ' parent'; ?>">
		<?php echo JHTML::link($caturl, $cattext, array('class'=>$active_menu)); ?>
	</dt>
	<dd<?php if($active_menu != "current" || !$category->childs){ ?> style="display: none;"<?php } ?> class="<?php echo $active_menu; ?>">
	<?php if ($category->childs) { ?>
	<dl class="menu<?php echo $class_sfx; ?>">
		<?php
		$temp = $categories;
		$categories = $category->childs;
		require JModuleHelper::getLayoutPath('mod_virtuemart_category', $params->get('layout', 'default').'_item');
		$categories = $temp; ?>
		</dl>
	<?php } ?>
	</dd>
<?php } ?>