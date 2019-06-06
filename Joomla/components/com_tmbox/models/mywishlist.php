<?php

// No direct access
defined('_JEXEC') or die;

class TmboxModelMywishlist extends JModelLegacy
{
   
    public function getProducts()
    {
        $user = JFactory::getUser();
        $prodIds = array();
        if (!$user->guest) {
            $db =& JFactory::getDBO();
            $q ="SELECT virtuemart_product_id FROM #__tmbox WHERE userid =".$user->id;
            $db->setQuery($q);
            $allproducts = $db->loadAssocList();
            foreach($allproducts as $productbd){
                $prodIds[] = $productbd['virtuemart_product_id'];
            }
        } else {
            $mainframe = JFactory::getApplication();
            $prodIds = $mainframe->getUserState( "com_tmbox.site.wishlistIds", array() );
        }
        $productModel = VmModel::getModel('product');
        $ratingModel = VmModel::getModel('ratings');
        $showRating = $ratingModel->showRating();
        $productModel->withRating = $showRating;
        $products = $productModel->getProducts($prodIds);
        $productModel->addImages($products, 1);

        return $products;
    }

}