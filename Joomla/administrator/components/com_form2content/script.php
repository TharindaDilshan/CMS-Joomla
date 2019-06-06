<?php
defined('_JEXEC') or die('Restricted acccess');

use \Joomla\Registry\Registry;

/**
 * Script file of Form2Content component
 */
class com_Form2ContentInstallerScript
{
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent) 
        {
        	$joomlaVersionRequired = '3.1.5';
        	
        	if(!$this->checkJoomlaVersion($joomlaVersionRequired))
        	{
        		JFactory::getApplication()->enqueueMessage(sprintf(JText::_('COM_FORM2CONTENT_JOOMLA_VERSION_TOO_LOW'), $joomlaVersionRequired), 'error');
        		return false;
        	}
        	
		 	if(!(extension_loaded('gd') && function_exists('gd_info')))
		 	{
		 		JFactory::getApplication()->enqueueMessage(JText::_('COM_FORM2CONTENT_GDI_NOT_INSTALLED'), 'warning');
		 	}
        	
        	return true;
        }
	
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) 
    {
    	$this->__createPath(JPATH_SITE . '/images/stories/com_form2content');
    	$this->__createPath(JPATH_SITE . '/media/com_form2content/templates');
    	$this->__createPath(JPATH_SITE . '/media/com_form2content/documents');
   		?>	
		<script type="text/javascript" src="<?php echo JURI::root(true).'/media/com_form2content/js/'; ?>jquery.blockUI.js"></script>
		<div align="left">
		<img src="../media/com_form2content/images/OSD_logo.png" width="350" height="180" border="0">
		<h2><?php JText::_('COM_FORM2CONTENT_WELCOME_TO_F2C'); ?></h2>
		</div>
		<script type="text/javascript">
			var jBusyInstalling = '<p class="blockUI" style="padding: 10px; margin: 10px;"><img src="<?php echo JURI::root(true).'/media/com_form2content/images/'; ?>busy.gif" /><span style="padding: 10px; margin: 10px; font-weight: bold; font-size: 1.5em;"><?php echo JText::_('COM_FORM2CONTENT_BUSY_INSTALLING_SAMPLE_DATA', true)?></span></p>';
	
			jQuery(document).ready(function() 
			{
				jQuery(document).ajaxStop(jQuery.unblockUI); 
				jQuery.blockUI({message: jBusyInstalling});
	
				jQuery.ajax({
				    type: 'POST',
				    dataType: 'JSON',
				    data: null,
				    url: 'index.php?option=com_form2content&task=project.installsamples&format=raw',
				    cache: false,
				    contentType: false,
				    processData: false,
				    success: function(data)
				    {
				    },
					error: function(jqXHR, textStatus, errorThrown)
					{
						alert(textStatus + '\r\n' + errorThrown);
					}
				});
			});	
		</script>		
		<?php        	
        }
 
        /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) 
    {
    }
 
        /**
     * method to update the component
     *
     * @return void
     */
        function update($parent) 
        {
        	$db = JFactory::getDBO();
        	
        	// Add missing fields
			$sql = 'INSERT INTO #__f2c_fieldtype (`id`, `description`) SELECT 9, \'Hyperlink\' FROM #__f2c_fieldtype Where id = 9 HAVING COUNT(*) = 0';
			$db->setQuery($sql);
			$db->execute();
			
			// add missing columns (release 4.6.0)
			$db->setQuery('SHOW COLUMNS FROM #__f2c_project LIKE \'images\'');
			
			if(!$db->loadResult())
			{
				$db->setQuery('ALTER TABLE #__f2c_project ADD COLUMN `images` TEXT NOT NULL  AFTER `metadesc`');
				$db->execute();
				$db->setQuery('ALTER TABLE #__f2c_project ADD COLUMN `urls` TEXT NOT NULL  AFTER `images`');
				$db->execute();
			}
			
			// Remove the sectionid column
			$db->setQuery('SHOW COLUMNS FROM #__f2c_form LIKE \'sectionid\'');
			
			if($db->loadResult())
			{
				$db->setQuery('ALTER TABLE #__f2c_form DROP COLUMN `sectionid`');
				$db->execute();
			}
			
			// add extended column (release 5.2.0)
			$db->setQuery('SHOW COLUMNS FROM #__f2c_form LIKE \'extended\'');
			
			if(!$db->loadResult())
			{
				$db->setQuery('ALTER TABLE #__f2c_form ADD COLUMN `extended` TEXT NOT NULL  AFTER `language`');
				$db->execute();
			}
			
			// add name column to fieldtype table (release 5.5.0)
			$db->setQuery('SHOW COLUMNS FROM #__f2c_fieldtype LIKE \'name\'');
			
			if(!$db->loadResult())
			{
				$db->setQuery('ALTER TABLE #__f2c_fieldtype ADD COLUMN `name` VARCHAR(45) NOT NULL  AFTER `description`');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Singlelinetext\' WHERE id = 1');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Multilinetext\' WHERE id = 2');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Editor\' WHERE id = 3');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Singleselectlist\' WHERE id = 5');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Image\' WHERE id = 6');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Hyperlink\' WHERE id = 9');
				$db->execute();
				$db->setQuery('UPDATE #__f2c_fieldtype set `name` = \'Infotext\' WHERE id = 11');
				$db->execute();
				// Change the id column to auto increment
				$db->setQuery('ALTER TABLE #__f2c_fieldtype MODIFY COLUMN id int(10) unsigned NOT NULL auto_increment');
				$db->execute();
			}

			// Add the modified_by column to the #__f2c_form table (release 5.6.0)
			$db->setQuery('SHOW COLUMNS FROM #__f2c_form LIKE \'modified_by\'');
			
			if(!$db->loadResult())
			{
				$db->setQuery('ALTER TABLE #__f2c_form ADD COLUMN `modified_by` int(10) unsigned NOT NULL DEFAULT \'0\' AFTER `modified`');
				$db->execute();
			}
			
			/*
			 * Release 5.5.3
			 */
			// Increase attribute column size from 10 to to 32
			$db->setQuery('ALTER TABLE #__f2c_fieldcontent MODIFY COLUMN attribute VARCHAR(32)');
			$db->execute();

			$adminFieldPath = JPATH_ADMINISTRATOR.'/components/com_form2content/models/fields/';
			
			// remove files that were present from an earlier version
			if(JFile::exists($adminFieldPath.'f2ccategory.php'))
			{
				JFile::delete($adminFieldPath.'f2ccategory.php');
			}
			
			if(JFile::exists($adminFieldPath.'f2ctemplate.php'))
			{
				JFile::delete($adminFieldPath.'f2ctemplate.php');
			}

			// Check if we need to upgrade to the new field structure where Joomla fields are modeled as F2C Fields
			$db->setQuery('SHOW COLUMNS FROM #__f2c_fieldtype LIKE \'classification_id\'');
			
			if(!$db->loadResult())
			{
				$db->setQuery('ALTER TABLE #__f2c_fieldtype ADD COLUMN `classification_id` smallint(6) NOT NULL DEFAULT \'1\' AFTER `name`');
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Id', 'Joomlaid', 0 FROM #__f2c_fieldtype WHERE name='Joomlaid' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Title', 'Joomlatitle', 0 FROM #__f2c_fieldtype WHERE name='Joomlatitle' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
			
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Alias', 'Joomlaalias', 0 FROM #__f2c_fieldtype WHERE name='Joomlaalias' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
			
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Meta Description', 'Joomlametadescription', 0 FROM #__f2c_fieldtype WHERE name='Joomlametadescription' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
			
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Meta Keywords', 'Joomlametakeywords', 0 FROM #__f2c_fieldtype WHERE name='Joomlametakeywords' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
			
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Category', 'Joomlacategory', 0 FROM #__f2c_fieldtype WHERE name='Joomlacategory' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
			
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Author', 'Joomlacreatedby', 0 FROM #__f2c_fieldtype WHERE name='Joomlacreatedby' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();

				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Author Alias', 'Joomlacreatedbyalias', 0 FROM #__f2c_fieldtype WHERE name='Joomlacreatedbyalias' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
			
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Access', 'Joomlaaccess', 0 FROM #__f2c_fieldtype WHERE name='Joomlaaccess' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();

				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Created Date', 'Joomlacreated', 0 FROM #__f2c_fieldtype WHERE name='Joomlacreated' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();

				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Start Publishing Date', 'Joomlapublishup', 0 FROM #__f2c_fieldtype WHERE name='Joomlapublishup' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'End Publishing Date', 'Joomlapublishdown', 0 FROM #__f2c_fieldtype WHERE name='Joomlapublishdown' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Featured', 'Joomlafeatured', 0 FROM #__f2c_fieldtype WHERE name='Joomlafeatured' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Language', 'Joomlalanguage', 0 FROM #__f2c_fieldtype WHERE name='Joomlalanguage' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'State', 'Joomlapublished', 0 FROM #__f2c_fieldtype WHERE name='Joomlapublished' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Tags', 'Joomlatags', 0 FROM #__f2c_fieldtype WHERE name='Joomlatags' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Associatons', 'Joomlaassociations', 0 FROM #__f2c_fieldtype WHERE name='Joomlaassociations' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$sql = "INSERT INTO #__f2c_fieldtype (`description`, `name`, `classification_id`) SELECT 'Template Selection', 'Joomlatemplate', 0 FROM #__f2c_fieldtype WHERE name='Joomlatemplate' HAVING COUNT(*) = 0";
				$db->setQuery($sql);
				$db->execute();
				
				$this->convertContentTypesToNewFieldStructure();			
			}
        }
 
   /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
    	// Set the custom translations on
    	$this->enableCustomTranslations();
    }
	
    function __createPath($path)
    {
		if(!JFolder::exists($path))
		{
			JFolder::create($path, 0775);
		}
    }
    
    private function checkJoomlaVersion($versionNumber)
    {
    	$version = new JVersion();
    	return $version->isCompatible($versionNumber);
    }
    
    private function convertContentTypesToNewFieldStructure()
    {
		if(!class_exists('F2cFactory'))
		{
			require_once(JPATH_SITE.'/components/com_form2content/factory.form2content.php');		
			require_once(JPATH_SITE.'/administrator/components/com_form2content/tables/project.php');			
			JLoader::registerPrefix('F2c', JPATH_SITE.'/components/com_form2content/libraries/form2content');			
		}
    	
		$legacyContentType = new F2cLegacyProject();
		$legacyContentType->upgradeAllContentTypes();
    }  

    private function enableCustomTranslations()
    {
		$db = JFactory::getDBO();		
		$db->setQuery('SELECT extension_id FROM #__extensions WHERE name=\'com_form2content\'');
		
		$extensionId = $db->loadResult();

    	$configTable =  JTable::getInstance('extension');
		$configTable->load($extensionId);
		
		$params = new Registry($configTable->params);
		$params->set('custom_translations', 1);
		
 		$configTable->params = $params->toString();
		$configTable->store();  				
    }
}
?>