<?php
 JFactory::getLanguage()->load('com_tmbox');
  VmConfig::loadConfig();
  VmConfig::loadJLang('com_virtuemart', true);
vmJsApi::addJScript("/components/com_tmbox/assets/scripts/tmbox.js",false,false);
$user = JFactory::getUser();
if ($user->guest) :
$mainframe = JFactory::getApplication();
$wishlistIds = $mainframe->getUserState("com_tmbox.site.wishlistIds", array());
//var_dump($wishlistIds);
?>
    <a class="add_wishlist hasTooltips <?php echo in_array($product->virtuemart_product_id, $wishlistIds) ? 'active' : ''; ?>"
       title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
       onclick="TmboxAddToWishlist('<?php echo $product->virtuemart_product_id; ?>');">
        <i class="fa fa-heart-o"></i>
        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
    </a>
<?php
else :
   $db =JFactory::getDBO();
   $q ="SELECT virtuemart_product_id FROM #__tmbox WHERE userid =".$user->id." AND virtuemart_product_id=".$product->virtuemart_product_id;
  $db->setQuery($q);
  $allproducts = $db->loadAssocList();
  foreach($allproducts as $productbd){
    $allprod['id'][] = $productbd['virtuemart_product_id'];
  }
  //var_dump($allproducts);
  $whishlistBD = isset($allprod['id']) ? $allprod['id'] : array();
    ?>
    <a class="add_wishlist hasTooltips <?php echo in_array($product->virtuemart_product_id, $whishlistBD) ? 'active' : ''; ?>"
       title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
       onclick="TmboxAddToWishlist('<?php echo $product->virtuemart_product_id; ?>');">
        <i class="fa fa-heart-o"></i>
        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
    </a>

<?php

endif; ?>


