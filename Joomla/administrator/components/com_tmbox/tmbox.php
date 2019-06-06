<?php

defined('_JEXEC') or die('Restricted access');

// Get controller instance
$controller = JControllerLegacy::getInstance('Tmbox');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
