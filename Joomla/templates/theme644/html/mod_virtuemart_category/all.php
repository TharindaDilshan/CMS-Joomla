<?php // no direct access
defined('_JEXEC') or die('Restricted access');
		//$app = JFactory::getApplication();
		//$doc = JFactory::getDocument();
		//$language  = $doc->language;
		//var_dump(VmConfig::$jDefLang);
		$db = JFactory::getDBO();
		$q  = 'SELECT `category_name`,`virtuemart_category_id` FROM `#__virtuemart_categories_'.VmConfig::$jDefLang.'` WHERE `virtuemart_category_id`='.$category_id;
		$db->setQuery($q);
		$categoriesParent = $db->loadAssoc();
		//print_r($categoriesParent['virtuemart_category_id']);
		//print_r($categoriesParent['category_name']);
		$active_menu = '';
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$categoriesParent['virtuemart_category_id']);
		$cattext = $categoriesParent['category_name'];

		if (in_array( $categoriesParent['virtuemart_category_id'], $parentCategories)) $active_menu .= ' active';
	?>
	<span class="iceModuleTile <?php echo $active_menu ?>">
				<?php echo JHTML::link($caturl, $cattext); ?>
			</span>
<ul class="menu<?php echo $class_sfx ?>" >
<?php  foreach ($categories as $category){

		$active_menu = '';
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
		$cattext = $category->category_name;
		//if ($active_category_id == $category->virtuemart_category_id) $active_menu = 'class="active"';
		if (in_array( $category->virtuemart_category_id, $parentCategories)) $active_menu .= ' active';
		if ($category->childs ) $active_menu .= ' parent'; ?>
<li class="<?php echo $active_menu ?>">
		<?php echo JHTML::link($caturl, $cattext); ?>
<?php if ($category->childs ) { ?>
<ul class="menu<?php echo $class_sfx; ?>">
<?php
	foreach ($category->childs as $child){
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
		$cattext = $child->category_name; ?>
<li>
	<?php echo JHTML::link($caturl, $cattext); ?>
</li>
<?php } ?>
</ul>
<?php } ?>
</li>
<?php } ?>
</ul>
