<?php

defined('_JEXEC') or die('Restricted access');

class TmboxViewSetting extends JViewLegacy
{

    protected $form;
    protected $params = null;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $model = $this->getModel();
        $item = $model->getItem(1);
       // $this->params = json_decode($item->get('setting'));
        $this->_addToolbar();
        parent::display($tpl);
    }

    protected function _addToolbar()
    {
        JToolBarHelper::title(JText::_('Component TmBox'));
        //JToolBarHelper::apply('setting.apply');
    }

}
