<?php

/**
 * @version    CVS: 4.2.2
 * @package    Com_Mdimpressum_pro
 * @author     Axel Metz <office@megrodesign.com>
 * @copyright  Copyright (C) 2016. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Class Mdimpressum_proFrontendHelper
 *
 * @since  1.6
 */
class Mdimpressum_proHelpersMdimpressum_pro
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_mdimpressum_pro/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_mdimpressum_pro/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'Mdimpressum_proModel');
		}

		return $model;
	}
}
