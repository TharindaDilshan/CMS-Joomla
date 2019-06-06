<?php
/**
 * @version    CVS: 4.2.2
 * @package    Com_Mdimpressum_pro
 * @author     Axel Metz <office@megrodesign.com>
 * @copyright  Copyright (C) 2016. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_mdimpressum_pro'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Mdimpressum_pro', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Mdimpressum_pro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
