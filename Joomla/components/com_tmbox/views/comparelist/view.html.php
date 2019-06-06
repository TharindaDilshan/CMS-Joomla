<?php
defined('_JEXEC') or die;

class TmboxViewComparelist extends JViewLegacy
{
    public $products;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_Tmbox', true);
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_COMPARE_PRODUCT'), JRoute::_('index.php?option=com_tmbox&view=comparelist'));
       
        $this->products = $this->get('Products');

        parent::display($tpl);
    }
}
