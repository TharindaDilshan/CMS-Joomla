<?php
// No direct access
defined('JPATH_BASE') or die;

use \Joomla\Registry\Registry;

/**
 * Factory class containing helper functions
 * 
 * This class contains factory methods for useful objects when building Form2Content applications
 * 
 * @package     Joomla.Site
 * @subpackage  com_form2content
 * @since       6.8.0
 */
abstract class F2cFactory
{
	public static $config = null;
	private static $arrContentType 	= array();
	
	/**
	 * Get a configuration object
	 *
	 * Returns the global {@link JRegistry} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return JRegistry object
	 */
	public static function getConfig()
	{
		if (!self::$config) 
		{
			self::$config = self::_createConfig();
		}

		return self::$config;
	}
	
	private static function _createConfig()
	{
		$config 		= new JRegistry();		
		$paramvalues 	= JComponentHelper::getParams('com_form2content');
		
		$config->loadString($paramvalues);
		
		$config->set('f2c_pro', false);
		$config->set('template_path', JPATH_SITE.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR);
		$config->set('images_path', 'images/stories/com_form2content');
		
		// Set some defaults
		if($config->get('generate_sample_template') == '')
		{
			$config->set('generate_sample_template', '1');
		}

		if($config->get('jpeg_quality') == '')
		{
			$config->set('jpeg_quality', '75');
		}
		
		if($config->get('date_format') == '')
		{
			$config->set('date_format', '%d-%m-%Y');
		}
		
		if($config->get('edit_items_level') == '')
		{
			$config->set('edit_items_level', '0');
		}
		
		return $config;
	}
	
	public static function getContentType($contentTypeId, $addToCache = true)
	{
		if(array_key_exists($contentTypeId, self::$arrContentType))
		{
			return self::$arrContentType[$contentTypeId];
		}

		// Load the Content Type and add it to the array
		if(!class_exists('Form2ContentModelProject'))
		{
			require_once(JPATH_SITE.'/components/com_form2content/models/project.php');
		}
		
		$model = new Form2ContentModelProject();
		$contentType = $model->getItem($contentTypeId);
		
		$f2cModelContenttype = new F2cModelContenttype($contentType);
		
		if($addToCache)
		{
			// Add the Content Type to the cache
			self::$arrContentType[$contentTypeId] = $f2cModelContenttype;
		}
		
		return $f2cModelContenttype;
	}
	
	/**
	 * Create a Dictionary of ContentField Types
	 *
	 * @param	boolean		$byId				True when the key is the id, otherwise the key is the name
	 *
	 * @return Dictionary of ContentField Types
	 */
	
	public static function getFieldTypesDictionary($byId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('*')->from('#__f2c_fieldtype');
		
		$db->setQuery($query);
		$fieldList = $db->loadObjectlist();
		
		$dicFields = array();
		
		foreach($fieldList as $field)
		{
			// Create the dictionary key based on id or on name
			$key = $byId ? $field->id : $field->name;
			$dicFields[$key] = $field;
		}
		
		return $dicFields;
	}
}
?>