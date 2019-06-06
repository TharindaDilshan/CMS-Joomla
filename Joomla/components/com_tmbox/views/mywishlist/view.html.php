<?php
// No direct access
defined('_JEXEC') or die;
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
class TmboxViewMywishlist extends JViewLegacy
{
    public $products;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_tmbox', true);
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_WISHLIST_PRODUCT'), JRoute::_('index.php?option=com_tmbox&view=mywishlist'));

        $document = JFactory::getDocument();
        $this->products = $this->get('Products');
        parent::display($tpl);
    }
}