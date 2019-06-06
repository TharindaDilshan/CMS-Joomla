<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once(JPATH_COMPONENT_SITE.'/controllers/formsbase.php');

class Form2ContentControllerForms extends Form2ContentControllerFormsBase
{
	function refresh()
	{
		$resultInfo	= array();
		$resultInfo['errors'] = array();
		$resultInfo['processed'] = 0;

		$backupErrorReporting = error_reporting();
		// turn off notices, because they might interfere with JSON results
		error_reporting(E_ALL & ~E_NOTICE);
		
		$ids = explode(',', JFactory::getApplication()->input->get('ids', '', 'RAW'));
		
		if(count($ids) == 0)
		{
			echo json_encode($resultInfo); 
			return;
		}
		
		// Get the model.
		$model = $this->getModel();

		// Make sure the item ids are integers
		JArrayHelper::toInteger($ids);

		// Refresh the items -> publish with F2C_STATE_RETAIN retains the current state.
		$model->batchRefresh($ids, F2C_STATE_RETAIN);

		foreach($model->getErrors() as $error)
		{
			$resultInfo['error'][] = $error;
		}
		
		$resultInfo['processed'] = count($ids);
		
		echo json_encode($resultInfo); 
		
		// reset error reporting
		error_reporting($backupErrorReporting);
	}	
}
?>