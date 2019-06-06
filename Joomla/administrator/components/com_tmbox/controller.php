<?php
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General controller class
 */
class TmboxController extends JControllerLegacy
{


    /**
     * display task
     *
     * @return void
     */
    public function display($cachable = false, $urlparams = false)
    {
        // set default view if not set
        $input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'setting'));

        // call parent behavior
        parent::display($cachable);
    }

}
