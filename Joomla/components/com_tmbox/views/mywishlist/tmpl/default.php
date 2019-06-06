<?php

defined('_JEXEC') or die;

        JFactory::getLanguage()->load('com_tmbox');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_virtuemart', true);
        $user = JFactory::getUser();
        $mainframe = JFactory::getApplication();
        $wishlistIds = $mainframe->getUserState("com_tmbox.site.wishlistIds", array());
        //var_dump($wishlistIds);
        if (!$user->guest) {
            if (isset($wishlistIds)) {
                $dbIds = $wishlistIds;
                $db =& JFactory::getDBO();
                $q ="SELECT virtuemart_product_id FROM #__tmbox WHERE userid =".$user->id;
                $db->setQuery($q);
                $allproducts = $db->loadAssocList();
                foreach($allproducts as $productbd){
                    $allprod['ids'][] = $productbd['virtuemart_product_id'];
                }
                $tmbox = isset($allprod['ids']) ? $allprod['ids'] : array();
                //print_r($productbd['virtuemart_product_id']);
                for($r=0; $r<count($dbIds); $r++) {
                    if(!in_array($dbIds[$r],$tmbox)) {
                   $q = "";
                    $q = "INSERT INTO `#__tmbox` (virtuemart_product_id,userid ) VALUES ('".$dbIds[$r]."','".$user->id."') ";
                    $db->setQuery($q);
                    $db->query();
                   }
               }
               //unset($wishlistIds);
           }
       }
       
?>

<div class="mywishlist">
    <h3 class="module-title">
        <i class="fa fa-heart-o"></i><?php echo JText::_('COM_TMBOX_WISHLIST_PRODUCT') ?>
    </h3>
<div class="clear"></div>
<div id="com_virtuemart">
<div class="comvirtuemartmod virtuemart-category__container list">
<?php
    if (!empty($this->products)) { 
    //if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
    $currency = CurrencyDisplay::getInstance( );
    $products_per_row = VmConfig::get ( 'products_per_row' );
    $ratingModel = VmModel::getModel('ratings');
    $showRating = $ratingModel->showRating();
    $layaoutwhishlist = 'whishlist';
    $productsLayout = VmConfig::get ( 'productsublayout', 'products' );
    $prod = array();
    $prod[0] = $this->products;
    echo shopFunctionsF::renderVmSubLayout($productsLayout,array('products'=>$prod,'currency'=>$currency,'products_per_row'=>$products_per_row,'showRating'=>$showRating, 'layaoutwhishlist' => $layaoutwhishlist));?>
    <div class="module-title wishlist no-products" style="display:none;">
        <i class="fa fa-info-circle"></i><?php echo JText::_('COM_TMBOX_NO_PRODUCTS_MYWHISHLIST'); ?>
    </div>  
    <?php }else { ?>
   <div class="module-title wishlist no-products">
        <i class="fa fa-info-circle"></i><?php echo JText::_('COM_TMBOX_NO_PRODUCTS_MYWHISHLIST'); ?>
    </div>  

   <?php  }?>
</div>
</div>
<?php


?>
</div>
