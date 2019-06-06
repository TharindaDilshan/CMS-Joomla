<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

class F2cFieldJoomlaCreatedByAlias extends F2cFieldBase
{	
	public function getPrefix()
	{
		return '';
	}
	
	public function reset()
	{
	}
	
	public function render($translatedFields, $contentTypeSettings, $parms = array(), $form, $formId)
	{
		return $form->getInput('created_by_alias');
	}
	
	public function prepareSubmittedData($formId)
	{
		return $this;
	}
	
	public function store($formid)
	{
		return array();		
	}
	
	public function renderLabel($translatedFields, $form = null)
	{
		return $this->title ? parent::renderLabel($translatedFields) : $form->getLabel('created_by_alias');
	}
	
	public function validate(&$data, $item)
	{
		// skip check when running in cron mode
		if(isset($data['isCron'])) return;
		
		$app 	= JFactory::getApplication();
		$isNew	= empty($data['id']);
		
		// get default value for title
		if(!$this->isFieldVisible())
		{
			$data['created_by_alias'] = $isNew ? '' : $item->created_by_alias;
		}		
	}
	
	public function export($xmlFields, $form)
	{
      	$xmlField = $xmlFields->addChild('field');
      	$xmlField->fieldname = $this->fieldname;
      	$xmlFieldContent = $xmlField->addChild('contentSingleTextValue');
      	$xmlFieldContent->value = $form->created_by_alias;
	}
	
	public function import($xmlField, $existingInternalData, &$data)
	{
		$data['created_by_alias'] = (string)$xmlField->contentSingleTextValue->value;
	}	
		
	public function addTemplateVar($templateEngine, $form)
	{
		$templateEngine->addVar(JString::strtoupper($this->fieldname), $form->created_by_alias);
		
		// legacy parameter
		$templateEngine->addVar('JOOMLA_AUTHOR_ALIAS', $form->created_by_alias);
	}
	
	public function setData($data)
	{
	}	
	
	public function getCssClass()
	{
		return 'f2c_created_by_alias';
	}	
	
	public function getClientSideValidationScript(&$validationCounter, $form)
	{		
		return '';
	}
	
	public function prepareValidation(&$item, &$data, $isNew)
	{
		$data['created_by_alias'] = $isNew ? $this->settings->get('default') : $item->created_by_alias;
	}
	
	public function canBeHiddenInFrontEnd()
	{
		return false;
	}
}
?>