<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

/**
 *
 */
class TmboxModelSetting extends JModelAdmin
{
    public function getTable($type = 'Setting', $prefix = 'TmboxTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        
    }
    protected function loadFormData()
    {
       
    }
    public function save($data)
    {
        
    }

}
