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

/**
 * Mdimpressum_pro helper.
 *
 * @since  1.6
 */
class Mdimpressum_proHelpersMdimpressum_pro
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{


JHtmlSidebar::addEntry(
			JText::_('COM_MDIMPRESSUM_PRO_TITLE_DASHBOARDS'),
			'index.php?option=com_mdimpressum_pro&view=dashboards',
			$vName == 'dashboards'
		);
JHtmlSidebar::addEntry(
			JText::_('COM_MDIMPRESSUM_PRO_TITLE_MDIMPRESSUMS'),
			'index.php?option=com_mdimpressum_pro&view=mdimpressums',
			$vName == 'mdimpressums'
		);
JHtmlSidebar::addEntry(
			JText::_('COM_MDIMPRESSUM_PRO_TITLE_LAWS'),
			'index.php?option=com_mdimpressum_pro&view=laws',
			$vName == 'laws'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_MDIMPRESSUM_PRO_TITLE_PRODATENSCHUTZM'),
			'index.php?option=com_mdimpressum_pro&view=prodatenschutzm',
			$vName == 'prodatenschutzm'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_MDIMPRESSUM_PRO_TITLE_PRODATEN2S'),
			'index.php?option=com_mdimpressum_pro&view=prodaten2s',
			$vName == 'prodaten2s'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_MDIMPRESSUM_PRO_TITLE_PRODATEN3S'),
			'index.php?option=com_mdimpressum_pro&view=prodaten3s',
			$vName == 'prodaten3s'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_mdimpressum_pro';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
