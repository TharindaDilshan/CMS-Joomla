<?php

defined('_JEXEC') or die;

class TmboxControllerMywishlist extends JControllerLegacy
{
    public function __construct()
    {
        parent::__construct();
        TmboxSiteHelper::loadVMLibrary();
    }

    public function add()
    {
        error_reporting('E_ALL');
        $jinput = JFactory::getApplication()->input;

        $mainframe = JFactory::getApplication();
        $wishlistIds = $mainframe->getUserState("com_tmbox.site.wishlistIds", array());

        $itemID = TmboxSiteHelper::getItemId('mywishlist');
        //var_dump($itemID);
         JFactory::getLanguage()->load('com_tmbox');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_virtuemart', true);
        $productModel = VmModel::getModel('product');

        $user = JFactory::getUser();
        if ($user->guest) {
            if (!in_array($jinput->get('product_id', null, 'INT'), $wishlistIds)) {
                $product = array($jinput->get('product_id', null, 'INT'));

                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                $wishlistIds[] = $jinput->get('product_id', null, 'INT');
                foreach ($prods as $product) {
                    $title = '' . JHTML::link(JRoute::_($product->link), $product->product_name) . '';
                    $btngomywishlist = '<a id="mywishlist_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=mywishlist&Itemid=' . $itemID . '') . '">' . JText::_('GO_TO_MYWISHLIST') . '</a>';
                    $totalwishlists = count($wishlistIds);
                    $success='successfully';
                }
                $this->showJSON('' . JText::_('COM_WHISHLIST_MASSEDGE_ADDED_NOTREG') . '', $title, $btngomywishlist, $totalwishlists, $success, '' . JText::_('COM_WHISHLIST_MASSEDGE_ADDED_NOTREG2') . '');
            } else {
                if (in_array($jinput->get('product_id', null, 'INT'), $wishlistIds)) {
                    $product = array($jinput->get('product_id', null, 'INT'));
                    $prods = $productModel->getProducts($product);
                    $productModel->addImages($prods, 1);
                    foreach ($prods as $product) {
                        $title = '' . JHTML::link(JRoute::_($product->link), $product->product_name) . '';
                        $btngomywishlist = '<a id="mywishlist_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=mywishlist&Itemid=' . $itemID . '') . '">' . JText::_('GO_TO_MYWISHLIST') . '</a>';
                        $totalwishlists = count($wishlistIds);
                        $success='notification';
                    }
                    $this->showJSON('' . JText::_('COM_WHISHLIST_MASSEDGE_ALLREADY_NOTREG') . '', $title, $btngomywishlist, $totalwishlists, $success, '' . JText::_('COM_WHISHLIST_MASSEDGE_ALLREADY_NOTREG2') . '');
                }
            }
        } else {
            $db =& JFactory::getDBO();
            $q ="SELECT virtuemart_product_id FROM #__tmbox WHERE userid =".$user->id;
            $db->setQuery($q);
            $allproducts = $db->loadAssocList();
            foreach($allproducts as $productbd){
                $allprod['ids'][] = $productbd['virtuemart_product_id'];
            }
            $tmboxWD = isset($allprod['ids']) ? $allprod['ids'] : array();
            //var_dump($sessionWD);
            if ((!in_array($jinput->get('product_id', null, 'INT'), $tmboxWD))) {
                $q = "";
                $q = "INSERT INTO `#__tmbox`
                        (virtuemart_product_id,userid )
                        VALUES
                        ('".$jinput->get('product_id', null, 'INT')."','".$user->id."') ";
                
                $db->setQuery($q);
                $db->query();
                $tmboxWDB = isset($allprod['id']) ? $allprod['id'] : array();
                
                if ((!in_array($jinput->get('product_id', null, 'INT'), $tmboxWDB))) {

                    $db =& JFactory::getDBO();
                    $q ="SELECT virtuemart_product_id FROM #__tmbox WHERE userid =".$user->id;
                    $db->setQuery($q);
                    $allproducts = $db->loadAssocList();
                    foreach($allproducts as $productbd){
                        $allprod['id'][] = $productbd['virtuemart_product_id'];
                    }
                    //var_dump ($allproducts);
                    //var_dump (count($allprod['id']));
                    $product = array($jinput->get('product_id', null, 'INT'));
                    $prods = $productModel->getProducts($product);
                    $productModel->addImages($prods,1);
                    //var_dump($prods);
                    foreach ($prods as $product) {

                        $title = '' . JHTML::link(JRoute::_($product->link), $product->product_name) . '';
                        $btngomywishlist = '<a id="mywishlist_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=mywishlist&Itemid=' . $itemID . '') . '">' . JText::_('GO_TO_MYWISHLIST') . '</a>';
                        $totalwishlists = count($allprod['id']);
                        $success='successfully';
                    }
                    $this->showJSON(''.JText::_('COM_WHISHLIST_MASSEDGE_ADDED_REG').'' , $title, $btngomywishlist, $totalwishlists, $success, ''.JText::_('COM_WHISHLIST_MASSEDGE_ADDED_REG2').'');
                }
            } else {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                foreach ($prods as $product) {
                    $title = '' . JHTML::link(JRoute::_($product->link), $product->product_name) . '';
                    $btngomywishlist = '<a id="mywishlist_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=mywishlist&Itemid=' . $itemID . '') . '">' . JText::_('GO_TO_MYWISHLIST') . '</a>';
                    $totalwishlists = count($allprod['id']);
                    $success='notification';
                }
                $this->showJSON('' . JText::_('COM_WHISHLIST_MASSEDGE_ALLREADY_REG') . '', $title, $btngomywishlist, $totalwishlists, $success, '' . JText::_('COM_WHISHLIST_MASSEDGE_ALLREADY_REG2') . '');

            }
        }
        $mainframe->setUserState("com_tmbox.site.wishlistIds", $wishlistIds);
        exit;
    }

    public function showJSON($message = '', $title = '', $btngomywishlist = '', $totalwishlists = '', $success = '', $message2 = '')
    {
        echo json_encode(array('message' => $message, 'title' => $title, 'totalwishlists' => $totalwishlists,  'btngomywishlist' => $btngomywishlist, 'success' => $success, 'message2' => $message2));

    }

    public function removed()
    {
        error_reporting('E_ALL');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_tmbox', true);
        $mainframe = JFactory::getApplication();
        $wishlistIds = $mainframe->getUserState("com_tmbox.site.wishlistIds", array());
        $jinput = JFactory::getApplication()->input;

        $productModel = VmModel::getModel('product');

        $user = JFactory::getUser();
        if ($user->guest) {

            if ($jinput->get('remove_id', null, 'INT')) {
                foreach ($wishlistIds as $k => $v) {
                    if ($jinput->get('remove_id', null, 'INT') == $v) {
                        unset($wishlistIds[$k]);
                    }
                }
                $prod = array($jinput->get('remove_id', null, 'INT'));
                $prods = $productModel->getProducts($prod);
                foreach ($prods as $product) {
                    $title = '<span>' . JHTML::link(JRoute::_($product->link), $product->product_name) . '</span>';
                }
                $totalrem = count($wishlistIds);
            }
            $this->removeJSON('' . JText::_('COM_TMBOX_MASSEDGE_REMOVE') . '', $totalrem);
        } else {
            $user = JFactory::getUser();
            $db =& JFactory::getDBO();
            $q = "DELETE  FROM `#__tmbox` WHERE virtuemart_product_id=".$jinput->get('remove_id', null, 'INT')." AND  userid =".$user->id;
            $db->setQuery($q);
            $db->query();
            $q ="SELECT virtuemart_product_id FROM #__tmbox WHERE userid =".$user->id;
            $db->setQuery($q);
            $allproducts = $db->loadAssocList();
            foreach($allproducts as $productbd){
                $allprod['ids'][] = $productbd['virtuemart_product_id'];
            }
            //var_dump($allprod['ids']);
            $prod = array($jinput->get('remove_id', null, 'INT'));
            $prods = $productModel->getProducts($prod);
            foreach ($prods as $product) {
                $title = '<span>' . JHTML::link(JRoute::_($product->link), $product->product_name) . '</span>';
            }
            $totalrem = count($allprod['ids']);
            unset($wishlistIds);
            $this->removeJSON('' . JText::_('COM_TMBOX_MASSEDGE_REMOVE') . '', $totalrem);
        }
        //var_dump($wishlistIds);
        $mainframe->setUserState("com_tmbox.site.wishlistIds", $wishlistIds);
        exit;
    }

    public function removeJSON($message = '', $totalrem = '')
    {
        echo json_encode(array('message' => $message, 'totalrem' => $totalrem));
    }
}