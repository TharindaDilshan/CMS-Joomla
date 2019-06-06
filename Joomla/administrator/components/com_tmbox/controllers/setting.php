<?php
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Setting controller class
 */
class TmboxControllerSetting extends JControllerAdmin
{

    /**
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }
    public function apply()
    {
       
    }

    
    public function getModel($name = '', $prefix = 'TmboxModel', $config = array())
    {
       // return parent::getModel($name, $prefix, $config);
    }

}
