<?php
defined('_JEXEC') or die('Restricted acccess');

jimport('joomla.application.component.controllerform');

class Form2ContentControllerProject extends JControllerForm
{
	public function __construct($config = array())
	{
		// Access check.
		if (!JFactory::getUser()->authorise('core.admin')) 
		{
			return JError::raiseError(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		parent::__construct($config);
	}

	public function getModel($name = 'Project', $prefix = 'Form2ContentModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function export()
	{
		$document	= JFactory::getDocument();
		$vName		= 'project';
		$vFormat	= 'raw';
		
		$document->setType($vFormat);

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) 
		{
			// Get the model for the view.
			$model = $this->getModel($vName);

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->document = $document;
			$view->display();
		}
	}
	
	public function InstallSamples()
	{
		require_once(JPATH_COMPONENT_ADMINISTRATOR.'/sample_data/samples.form2content.php');
		
		$sampleDataHelper = new F2cSampleDataHelper();
		$sampleDataHelper->install();
		
		ob_clean();	
		echo '';
	}
	
	/**
	 * Method to gather all the Template Parameters for a given Content Type into a multiselect list.
	 * This function will be called from AJAX
	 *
	 * @return  string		HTML containing the multiselect list options
	 * 
	 * @since   6.17.0
	 */
	public function getTemplateParameters()
	{
		$contentType 		= F2cFactory::getContentType($this->input->getInt('contenttypeid'));
		$templateType 		= $this->input->getString('templatetype');
		$output 			= '';
		$templateParameters = array('general' => array(), 'joomla' => array(), 'custom' => array());
		
		if($templateType == 'article')
		{
			// Article intro/main template
			$templateParameters['general'] = array('JOOMLA_ID','JOOMLA_ARTICLE_LINK','JOOMLA_ARTICLE_LINK_SEF','JOOMLA_MODIFIED',
								   				   'JOOMLA_MODIFIED_RAW','JOOMLA_MODIFIED_BY',
								   				   'F2C_IMAGES_PATH_RELATIVE', 'F2C_IMAGES_PATH_ABSOLUTE');
			
			foreach($contentType->fields as $field)
			{
				$index = $field->classificationId == F2C_FIELD_JOOMLA_NATIVE ? 'joomla' : 'custom';
				
				foreach($field->getTemplateParameterNames() as $templateParameterName)
				{
					$templateParameters[$index][] = $templateParameterName;
				}
			}		
		}
		else 
		{
			// Form submission template
			$templateParameters['general'] = array('F2C_BUTTON_APPLY', 'F2C_BUTTON_SAVE', 'F2C_BUTTON_SAVE_AND_NEW',
												   'F2C_BUTTON_SAVE_AS_COPY', 'F2C_BUTTON_CANCEL');		
			
			foreach($contentType->fields as $field)
			{
				$uFieldname 					= JString::strtoupper($field->fieldname);
				$index 							= $field->classificationId == F2C_FIELD_JOOMLA_NATIVE ? 'joomla' : 'custom';
				$templateParameters[$index][] 	= $uFieldname;
				$templateParameters[$index][] 	= $uFieldname.'_CAPTION';
			}		
		}		
		
		// Format the output
		$output .= $this->formatOptGroup($templateParameters['general'], 'COM_FORM2CONTENT_GENERAL_PARAMETERS');
		$output .= $this->formatOptGroup($templateParameters['joomla'], 'COM_FORM2CONTENT_JOOMLA_FIELDS_PARAMETERS');
		$output .= $this->formatOptGroup($templateParameters['custom'], 'COM_FORM2CONTENT_CUSTOM_FIELDS_PARAMETERS');

		echo json_encode($output);
		
		// Close the application
		JFactory::getApplication()->close();
	}
	
	private function formatOptGroup($templateParameters, $title)
	{
		$output = '<optgroup label="'.JText::_($title).'">';	
		
		foreach($templateParameters as $parm)
		{
			$output .= '<option value="'.$parm.'">{$'.$parm.'}</option>';
		}
		
		$output .= '</optgroup>';			
		
		return $output;
	}
}
?>