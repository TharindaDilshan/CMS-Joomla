<?php
/**
 * @version    CVS: 4.2.2
 * @package    Com_Mdimpressum_pro
 * @author     Axel Metz <office@megrodesign.com>
 * @copyright  Copyright (C) 2016. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Mdimpressum_pro', JPATH_COMPONENT);

// Execute the task.
$controller = JControllerLegacy::getInstance('Mdimpressum_pro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
