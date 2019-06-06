<?php


defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Setting table class
 */
class TmboxTableSetting extends JTable
{

    public $id = null;
    public $setting = null;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__tmbox', 'id', $db);
    }

    /**
     * Table fields checking
     * @return type
     */
    
}
