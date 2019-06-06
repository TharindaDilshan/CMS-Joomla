<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

use \Joomla\Registry\Registry;

require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'project.php');
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'formbase.php');

class Form2ContentModelForm extends Form2ContentModelFormBase
{
	public function save($data)
	{
		if($data['created'])
		{
			if($date = F2cDateTimeHelper::ParseDate($data['created'], '%Y-%m-%d'))
			{
				$data['created'] = $date->toSql();			
			}
			else
			{
				$this->setError(JText::sprintf(JText::_('COM_FORM2CONTENT_ERROR_DATE_FIELD_INCORRECT_DATE'), JText::_('COM_FORM2CONTENT_FIELD_CREATED_LABEL'), $translatedDateFormat));
				return false;
			}
		}
				
		if($data['publish_up'])
		{
			if($date = F2cDateTimeHelper::ParseDate($data['publish_up'], '%Y-%m-%d'))
			{
				$data['publish_up'] = $date->toSql();			
			}
			else
			{
				$this->setError(JText::sprintf(JText::_('COM_FORM2CONTENT_ERROR_DATE_FIELD_INCORRECT_DATE'), JText::_('COM_FORM2CONTENT_FIELD_PUBLISH_UP_LABEL'), $translatedDateFormat));
				return false;
			}
		}

		if($data['publish_down'])
		{
			if($date = F2cDateTimeHelper::ParseDate($data['publish_down'], '%Y-%m-%d'))
			{
				$data['publish_down'] = $date->toSql();			
			}
			else
			{
				$this->setError(sprintf(JText::_('COM_FORM2CONTENT_ERROR_DATE_FIELD_INCORRECT_DATE'), JText::_('COM_FORM2CONTENT_FIELD_PUBLISH_DOWN_LABEL'), $translatedDateFormat));
				return false;
			}
		}
		
		return parent::save($data);
	}

	public function getJArticle($id) 
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Construct the query
		$query->select('*');
		$query->from('#__content');
		$query->where('id = ' . (int)$id);		
		// Setup the query
		$db->setQuery($query->__toString());
		
		$obj = $db->loadObject();
		
		if(!$obj)
		{
			$obj = new JObject();
			$obj->hits = 0;
			$obj->version = 0;
			$obj->modified = null;
			$obj->modified_by = null;
		}
		
		return $obj;
	}
	
	/*
	 * Support batch processing of the forms
	 */
	public function batch($commands, $pks, $contexts)
	{
		if(parent::batch($commands, $pks, $contexts))
		{
			// refresh the forms
			$modelForm = new Form2ContentModelForm(array('ignore_request' => true));
			return $modelForm->publish($pks, F2C_STATE_RETAIN);
		}
	}
	
	public function batchRefresh(&$pks, $value = 1, $batchImportMode = false)
	{
		// Initialise variables.
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();
		$table		= $this->getTable();
		$pks		= (array)$pks;
		$errors 	= array();
		
		// Create a structure that hold the Joomla Ids for every possible state
		$stateChanges = array(F2C_STATE_PUBLISHED => array(), F2C_STATE_UNPUBLISHED => array(), F2C_STATE_TRASH => array());		
		
		// Include the content plugins for the change of state event.
		JPluginHelper::importPlugin('content');

		// Access checks.
		foreach ($pks as $i => $pk) 
		{
			$table->reset();

			if ($table->load($pk)) 
			{
				if($value == F2C_STATE_TRASH)
				{  
					if($this->canTrash($table) || $batchImportMode)
					{
						if($table->reference_id)
						{
							if($value == F2C_STATE_RETAIN)
							{
								$stateChanges[$table->state][] = $table->reference_id;
							}
							else 
							{ 
								$stateChanges[$value][] = $table->reference_id;
							}
						}
					}
					else 
					{
						// Prune items that you can't change.
						unset($pks[$i]);
						$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED').' (ID = '.$i.')');
					}
				}
				else 
				{
					if ($this->canEditState($table) || $batchImportMode) 
					{
						if($table->reference_id)
						{
							if($value == F2C_STATE_RETAIN)
							{
								$stateChanges[$table->state][] = $table->reference_id;
							}
							else 
							{
								$stateChanges[$value][] = $table->reference_id;
							}
						}
					}
					else 
					{
						// Prune items that you can't change.
						unset($pks[$i]);
						$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED').' (ID = '.$i.')');
					}
				}				
			}
		}

		// Attempt to change the state of the records.
		foreach ($pks as $i => $pk) 
		{ 	
			$this->setState($this->getName().'.id', $pk);
			
			if(!$this->parse($value, false))
			{
				// continue with next id
			}
		}
		
		// Trigger the onContentChangeState event.
		foreach($stateChanges as $state => $joomlaIds)
		{
			if(count($joomlaIds))
			{
				$result = $dispatcher->trigger('onContentChangeState', array('com_content.article', $joomlaIds, $state));
		
				if (in_array(false, $result, true)) 
				{
					$this->setError($table->getError());
				}
			}
		}
		
		// Clear the F2C and content cache
		$cache = JFactory::getCache($this->option);
		$cache->clean();
		$cache = JFactory::getCache('com_content');
		$cache->clean();
	}
	
	/**
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $value     The new category.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since	4.6.0
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId = (int)$value;
		$i = 0;

		// Check that the category exists
		if ($categoryId)
		{
			$categoryTable = JTable::getInstance('Category');
			if (!$categoryTable->load($categoryId))
			{
				if ($error = $categoryTable->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
					return false;
				}
			}
		}

		if (empty($categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
			return false;
		}
		
		// Check that the user has create permission for the component
		$user = JFactory::getUser();

		if (!$user->authorise('core.create', 'com_form2content'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}
		
		$modelForm = new Form2ContentModelForm(array('ignore_request' => true));
		$newIds = $modelForm->copy($pks, $categoryId);		
		
		// Clean the cache
		$this->cleanCache();

		return $newIds;		
	}
	
	/**
	 * Batch tag a list of item.
	 *
	 * @param   integer  $value     The value of the new tag.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  void.
	 *
	 * @since   5.4.0
	 */
	protected function batchTag($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($user->authorise('core.edit', $contexts[$pk]))
			{
				$table->reset();
				$table->load($pk);
				
				$tags 		= array($value);
				$extended	= new JRegistry($table->extended);
				
				if($extended->get('tags') != '')
				{
					$oldTags = explode(',', $extended->get('tags'));
				}
				else 
				{
					$oldTags = array();
				}
				
				if(count($tags))
				{
					foreach($tags as $tag)
					{
						if(!array_key_exists($tag, $oldTags))
						{
							$oldTags[] = $tag;
						}
					}
				}
				
				$extended = new JRegistry();
				$extended->set('tags', implode(',',$oldTags));
				
				$table->extended = $extended->toString();
				$table->store();
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}
	
	/*
	 * Special save function for import.
	 * This function skips all user related checks.
	 */
	public function saveCron($data)
	{
		return parent::save($data);
	}
}
?>