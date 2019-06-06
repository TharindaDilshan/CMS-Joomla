<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

class F2cFieldAdminJoomlaCategory extends F2cFieldAdminBase
{
	public $defaultFieldLabel = 'JCATEGORY';
		
	function display($form, $item)
	{
	}
	
	public function clientSideValidation($view)
	{
		?>
		if(jQuery('#jform_frontvisible0').is(':checked') && jQuery('#jform_settings_default').val() == '-1')
		{
			alert("<?php echo JText::_('COM_FORM2CONTENT_ERROR_PROJECT_SECTION_CATEGORY_DEFAULT_EMPTY', true); ?>");
			return false;
		}
		<?php 	
	}
	
	public function prepareSave(&$data, $useRequestData)
	{
		$data['settings']['error_message_required'] = '';
		$data['settings']['requiredfield'] = 1;
	}
	
	/**
	 * Method to generate template code for the sample template
	 *
	 * @param   string	$fieldname	Name of the field
	 *
	 * @return  string	Generated template code
	 *
	 * @since   6.17.0
	 */
	public function getTemplateSample($fieldname)
	{
		// No template sample
		return 	'';
	}		
}
?>