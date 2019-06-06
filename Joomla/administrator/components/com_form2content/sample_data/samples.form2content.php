<?php
defined('_JEXEC') or die('Restricted acccess');

require_once(JPATH_COMPONENT.'/models/project.php');
require_once(JPATH_COMPONENT.'/models/form.php');

class F2cSampleDataHelper
{
	/**
	 * Install the samples from the sample_data folder
	 * 
	 * @return	void
	 * 
	 * @since   5.6.0
	 */
	public function install()
	{
		$samplebase = JPATH_COMPONENT_ADMINISTRATOR.'/sample_data/';
					
		if($contentType = $this->importContentType($samplebase.'Blog articles_f2c_lite_contenttype.xml'))
		{
			$this->importArticle($samplebase.'Blog articles_f2c_lite_form1.xml');
			$this->importArticle($samplebase.'Blog articles_f2c_lite_form2.xml');
		}
	}
	
	/**
	 * Get the first available category path on the website
	 * 
	 * @return	string	path to category
	 * 
	 * @since   6.17.0
	 */
	private function getDefaultCategoryPath()
	{
		$db 	= JFactory::getDbo();		
		$query	= $db->getQuery(true);
		
		$query->select('path')->from('#__categories')->where('extension = \'com_content\' AND published = 1');
		
		$db->setQuery($query, 0, 1);
		
		return '/'.$db->loadResult();
	}
	
	/**
	 * Import a Content Type based on an XML file
	 * 
	 * @param	string	Path to XML file
	 * 
	 * @return	object	Imported Content Type
	 * 
	 * @since   6.17.0
	 */
	private function importContentType($filename)
	{
		$model 	= new Form2ContentModelProject();
		
		if(!$model->import($filename))
		{
			// Check for errors.
			if (count($errors = $model->getErrors())) 
			{
				JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'notice');
				echo implode("\n", $errors);
			}
			
			return false;
		}
		
		return F2cFactory::getContentType($model->getState('project.id'));
	}
	
	private function importArticle($importFile)
	{
		$db				= JFactory::getDbo();
		$f2cConfig 		= F2cFactory::getConfig();
		$form 			= new Form2ContentModelForm();
		$catPath 		= $this->getDefaultCategoryPath();
		$articleImport 	= new F2cIoImportarticle($db, $f2cConfig, $form);
		$xml 			= $articleImport->loadXmlFile($importFile);
		
		foreach ($xml[1] as $xmlForm)
		{
			$xmlForm->registerXPathNamespace('f', 'http://schemas.form2content.com/forms');
			// Set the category to a known category for the user's site
			$nodeList = $xmlForm->xpath('.//f:field[f:fieldname="category"]');
			$nodeList[0]->value = $catPath;
		}
		
		$articleImport->importXml($xml[1]);
	}
}
?>