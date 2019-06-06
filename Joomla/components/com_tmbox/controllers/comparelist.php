<?php
defined('_JEXEC') or die;
class TmboxControllerComparelist extends JControllerLegacy
{
    public function __construct()
    {
        parent::__construct();
        TmboxSiteHelper::loadVMLibrary();
    }


    public function add()
    {
        $mainframe = JFactory::getApplication();
        $compareIds = $mainframe->getUserState("com_tmbox.site.compareIds", array());
        //var_dump($compareIds);
        $jinput = JFactory::getApplication()->input;
        JFactory::getLanguage()->load('com_tmbox');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_tmbox', true);

        $itemId = TmboxSiteHelper::getItemId('comparelist');
        //var_dump($itemId);

        $productModel = VmModel::getModel('product');

        if (isset($compareIds) && (!in_array($jinput->get('product_id', null, 'INT'), $compareIds)) && (count($compareIds) <= 3)) {

            $product = array($jinput->get('product_id', null, 'INT'));
            $prods = $productModel->getProducts($product);
            $productModel->addImages($prods, 1);
            $compareIds[] = $jinput->get('product_id', null, 'INT');
            foreach ($prods as $product) {

                $title = '<div class="title">' . JHTML::link(JRoute::_($product->link), $product->product_name) . '</div>';
                
                $btngocompare = '<a id="compare_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=comparelist&Itemid=' . $itemId . '') . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                if (!empty($compareIds)) {
                    $totalcompare = count($compareIds);
                }
                $success='successfully';
            }
            $this->showJSON('' . JText::_('COM_COMPARE_MASSEDGE_ADDED') . '', $title, $btngocompare, $totalcompare, $success, '' . JText::_('COM_COMPARE_MASSEDGE_ADDED2') . '');

        } else {
            if (!in_array($jinput->get('product_id', null, 'INT'), $compareIds)) {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                foreach ($prods as $product) {
                    $title = '';
                    $btngocompare = '<a id="compare_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=comparelist&Itemid=' . $itemId . '') . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                    if (!empty($compareIds)) {
                        $totalcompare = count($compareIds);
                    }
                    $success='warning';
                }
                $this->showJSON('<span class="warning">' . JText::_('COM_COMPARE_MASSEDGE_MORE') . '</span>', '', $btngocompare, $totalcompare, $success);
            } else {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                foreach ($prods as $product) {
                    $title = '<div class="title">' . JHTML::link(JRoute::_($product->link), $product->product_name) . '</div>';
                    $btngocompare = '<a id="compare_go" class="button" rel="nofollow" href="' . JRoute::_('index.php?option=com_tmbox&view=comparelist&Itemid=' . $itemId . '') . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                    if (!empty($compareIds)) {
                        $totalcompare = count($compareIds);
                    }
                    $success='notification';    
                }
                $this->showJSON('' . JText::_('COM_COMPARE_MASSEDGE_ALLREADY') . '', $title, $btngocompare, $totalcompare, $success,'' . JText::_('COM_COMPARE_MASSEDGE_ALLREADY2') . '');
            }
        }
        $mainframe->setUserState("com_tmbox.site.compareIds", $compareIds);
        exit;
    }

    public function showJSON($message = '', $title = '', $btngocompare = '', $totalcompare = '', $success = '', $message2 = '')
    {
        echo json_encode(array('message' => $message, 'title' => $title, 'totalcompare' => $totalcompare, 'btngocompare' => $btngocompare, 'success' => $success, 'message2' => $message2,));
    }


    public function removed()
    {

        VmConfig::loadConfig();
        VmConfig::loadJLang('com_tmbox', true);
        $mainframe = JFactory::getApplication();
        $compareIds = $mainframe->getUserState("com_tmbox.site.compareIds", array());
        $jinput = JFactory::getApplication()->input;

        $productModel = VmModel::getModel('product');

        if ($jinput->get('remove_id', null, 'INT')) {
            foreach ($compareIds as $k => $v) {
                if ($jinput->get('remove_id', null, 'INT') == $v) {
                    unset($compareIds[$k]);
                }
            }
            $prod = array($jinput->get('remove_id', null, 'INT'));
            $prods = $productModel->getProducts($prod);
            foreach ($prods as $product) {
                $title = '<span>' . JHTML::link($product->link, $product->product_name) . '</span>';
            }
            $totalrem = count($compareIds);
        }
        $mainframe->setUserState("com_tmbox.site.compareIds", $compareIds);
        $this->removeJSON('' . JText::_('COM_COMPARE_MASSEDGE_REM') . ' ' . $title . ' ' . JText::_('COM_COMPARE_MASSEDGE_REM2') . '', $totalrem);
        exit;
    }
     public function removeJSON($message = '', $totalrem = '')
    {
        echo json_encode(array('message' => $message, 'totalrem' => $totalrem));
    }
}