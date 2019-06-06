<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

use \Joomla\Registry\Registry;

require_once(JPATH_SITE.'/components/com_form2content/shared.form2content.php');
require_once(JPATH_SITE.'/components/com_form2content/utils.form2content.php');
require_once(JPATH_SITE.'/components/com_form2content/helpers/form2content.php');

jimport('joomla.application.component.modeladmin');
jimport('joomla.form.form');
jimport('joomla.user.helper');

class Form2ContentModelFormBase extends JModelAdmin
{
	protected $text_prefix 					= 'COM_FORM2CONTENT';
	protected $event_before_save 			= 'onBeforeF2cContentSave';
	protected $event_after_save 			= 'onAfterF2cContentSave';
	protected $event_before_delete 			= 'onBeforeF2cContentDelete';
	protected $event_after_delete 			= 'onAfterF2cContentDelete';
	protected $event_before_parse 			= 'onBeforeF2cContentParse';
	protected $event_after_parse 			= 'onAfterF2cContentParse';	
	protected $event_change_state 			= 'onF2cContentChangeState';
	protected $f2cConfig 					= null;
	protected $parsedIntroContent 			= null;
	protected $parsedMainContent 			= null;
	public 	  $contentTypeId				= 0;
	public 	  $classicLayout				= false;
	protected $dicContentTypeTitle			= array();
	public 	  $cachedItem					= null;
			
	/**
	 * The type alias for this content type (this type is present only to prevent errors).
	 *
	 * @var      string
	 * @since    5.4.0
	 */
	public $typeAlias = 'com_form2content.form';
	
	function __construct($config = array())
	{
		parent::__construct($config);
	
		$jinput = JFactory::getApplication()->input;
		
		// Load the component parameters
		$this->f2cConfig = F2cFactory::getConfig();
		
		// try to load the contentType
		$this->contentTypeId = $jinput->getInt('projectid', $jinput->getInt('contenttypeid'));
		
		if($this->contentTypeId == 0)
		{
			if(array_key_exists('jform', $_POST))
			{
				$this->contentTypeId = (int)$_POST['jform']['projectid'];
			}
		}
	}

	public function getTable($type = 'Form', $prefix = 'Form2ContentTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getCachedItem($pk = null)
	{
		return $this->cachedItem;
	}
	
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) 
		{
			// Convert the params field to an array.
			$registry = new Registry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();

			// Convert the metadata field to an array.
			$registry = new Registry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();
			
			// Add the category title and alias
			$query = $this->_db->getQuery(true);
			
			$query->select('title, alias');
			$query->from('`#__categories`');		
			$query->where('id = '.(int)$item->catid);
			
			$this->_db->setQuery($query);

			if($category = $this->_db->loadObject())
			{
				$item->catTitle = $category->title;
				$item->catAlias = $category->alias;
			}
			else
			{
				$item->catTitle = '';
				$item->catAlias = '';
			}
			
			$item->tags = array();
			
			// Load item tags
            if (!empty($item->id))
            {
              	$item->extended = new Registry($item->extended);
              	$tagList = $item->extended->get('tags');
                	
               	if(!empty($tagList))
               	{
					$item->tags = explode(',', $tagList);
               	}               	
            }
            
			// Load associated content items
			$app = JFactory::getApplication();
			$assoc = JLanguageAssociations::isEnabled();
	
			if ($assoc)
			{
				$item->associations = array();
	
				if ($item->id != null)
				{
					$associations = JLanguageAssociations::getAssociations('com_content', '#__content', 'com_content.item', $item->reference_id);
	
					foreach ($associations as $tag => $association)
					{
						$item->associations[$tag] = $association->id;
					}
				}
			}
		}

		if(!$item->id)
		{
			// new Form: initialize some values	
			$contentTypeId 			= $this->contentTypeId;
			$contentType			= F2cFactory::getContentType($contentTypeId);
			
			$item->projectid 		= $contentTypeId;
			$item->attribs			= $contentType->attribs;
			$item->metadata			= $contentType->metadata;	
			$item->metadesc			= $contentType->metadesc;
			$item->metakey			= $contentType->metakey;
			$item->fields			= array();
		}
				
		$formId = $item->id ? (int)$item->id : 0;
		
		// Load the custom F2C fields data. When the item is empty, load the field definitions
		$query = $this->_db->getQuery(true);
		
		$query->select('pf.*, ft.name AS fieldtypename, ft.classification_id');
		$query->from('#__f2c_projectfields pf');
		$query->where('pf.projectid = '.(int)$item->projectid);
		$query->select('fc.attribute, fc.content, fc.id AS fieldcontentid, IFNULL(fc.formid, ' . $formId . ') as formid');
		$query->join('LEFT', '#__f2c_fieldcontent fc ON fc.fieldid = pf.id AND fc.formid = '. $formId);
		$query->select('ft.name');
		$query->join('INNER', '#__f2c_fieldtype ft ON pf.fieldtypeid = ft.id');
		$query->order('pf.ordering, pf.fieldtypeid, fc.id');

		$this->_db->setQuery($query);
		
		$fieldContentList = $this->_db->loadObjectList();
		
		if(count($fieldContentList))
		{
			$formsData = $this->createFormDataObjects($fieldContentList);	
			$item->fields = $formsData[(int)$item->id];
		}
		else 
		{
			$item->fields = array();
		}
		if(!$item->id)
		{
			foreach($item->fields as $field)
			{
				$field->setDefaultValue($item);
			}
		}		

		return $item;
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();
		
		// Get the form.
		$form = $this->loadForm('com_form2content.form', 'form', array('control' => 'jform', 'load_data' => $loadData));
						
		if (empty($form)) 
		{
			return false;
		}

		if((int)$form->getValue('projectid'))
		{
			$contentTypeId = (int)$form->getValue('projectid');
		}
		else
		{
			$contentTypeId = $data['projectid'];			
		}

		// Determine correct permissions to check.
		if ($id = (int)$this->getState('form.id')) 
		{
			// Existing record. Can only edit in selected categories.
			//$form->setFieldAttribute('catid', 'action', 'core.edit');
			// Existing record. Can only edit own articles in selected categories.
			//$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else 
		{
			// New record. Can only create in selected categories.
			//$form->setFieldAttribute('catid', 'action', 'core.create');
		}
		
		$dataObject 			= new JObject();
		$dataObject->id 		= $form->getValue('id');
		$dataObject->projectid 	= $contentTypeId;
		$dataObject->created_by = $form->getValue('created_by');
		
		if(empty($dataObject->id))
		{
			$dataObject->id 		= isset($data['id']) ? $data['id'] : 0;
			$dataObject->created_by = isset($data['created_by']) ? (int)$data['created_by'] : -1;
		}
		
		// Modify the form based on Edit State access controls.
		if (!$this->canEditState($dataObject))
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');
		}
		
		// Set the correct formats of the date fields
		$dateFormat = $this->f2cConfig->get('date_format');
		$form->setFieldAttribute('created', 'format', $dateFormat);
		$form->setFieldAttribute('modified', 'format', $dateFormat);
		$form->setFieldAttribute('publish_up', 'format', $dateFormat);
		$form->setFieldAttribute('publish_down', 'format', $dateFormat);
		
		$form->setFieldAttribute('created', 'classiclayout', $this->classicLayout);
		$form->setFieldAttribute('modified', 'classiclayout', $this->classicLayout);
		$form->setFieldAttribute('publish_up', 'classiclayout', $this->classicLayout);
		$form->setFieldAttribute('publish_down', 'classiclayout', $this->classicLayout);
		$form->setFieldAttribute('intro_template', 'classiclayout', $this->classicLayout);
		$form->setFieldAttribute('main_template', 'classiclayout', $this->classicLayout);
		$form->setFieldAttribute('created_by', 'classiclayout', $this->classicLayout);
		
		// Prevent messing with article language and category when editing existing article with associations
		$assoc = JLanguageAssociations::isEnabled();

		// Check if article is associated
		if (!empty($dataObject->id) && $app->isClient('site') && $assoc)
		{
			$item = $this->getItem($dataObject->id);

			$associations = JLanguageAssociations::getAssociations('com_content', '#__content', 'com_content.item', $item->reference_id);

			// Make fields read only
			if ($associations)
			{
				$form->setFieldAttribute('language', 'readonly', 'true');
				$form->setFieldAttribute('catid', 'readonly', 'true');
		//		$form->setFieldAttribute('language', 'filter', 'unset');
		//		$form->setFieldAttribute('catid', 'filter', 'unset');
			}
		}
		
		return $form;
	}

	public function save($data)
	{
		$isNew			= empty($data['id']);
		$app			= JFactory::getApplication();
		$dispatcher 	= JDispatcher::getInstance();
		$config 		= JFactory::getConfig();
		$jinput			= JFactory::getApplication()->input;
		$table			= $this->getTable();
		$key			= $table->getKeyName();
		$pk				= (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName().'.id');
		$task			= $jinput->get('task');
		$isCron 		= (array_key_exists('isCron', $data) && $data['isCron'] == true);
		$contentType 	= F2cFactory::getContentType($data['projectid']);		
		$fields			= $contentType->fields;
		$formOld 		= null;
		$fieldsOld 		= null;
		
		// Get the current date and time
		$dateNow = JFactory::getDate('now', 'UTC');
		$dateNow->setTimezone(new DateTimeZone($config->get('offset')));
		$dateNow = $dateNow->toSql();								
		
		if(!$isNew)
		{
			// Load the existing form
			$formOld 	= $this->getItem($data['id']);
			$fieldsOld	= $formOld->fields;
		}
		
		if ($pk > 0) 
		{
			$table->load($pk);
		}
		
		if(!array_key_exists('alias', $data))
		{
			$data['alias'] = $pk > 0 ? $table->alias : '';
		}
		
		// Alter the title for save as copy
		if ($task == 'save2copy') 
		{
			list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['title']	= $title;
			$data['alias']	= $alias;
			$data['state']	= F2C_STATE_UNPUBLISHED;
		}
						
		$categoryInfo = $this->getCategoryInfo($data['catid']);
		
		$data['modified'] 		= $dateNow;
		$data['reference_id'] 	= $isNew ? null : $table->reference_id;
		$data['catAlias'] 		= $categoryInfo->alias;
		$data['catTitle'] 		= $categoryInfo->title;
	
		if(array_key_exists('preparedFieldData', $data))
		{
			$fields = $data['preparedFieldData'];
		}
		else 
		{
			foreach($fields as $fieldId => $field)
			{
				// Change $fields index from numeric (id) to string (fieldname)
				$fields[$field->fieldname] = $field->prepareSubmittedData($pk);
				unset($fields[$fieldId]);
			}
		}
		
		if(!$this->validateFields($data, $fields))
		{
			return false;
		}

		JPluginHelper::importPlugin('form2content');
		
		$newFormData = $this->convertArrayToObject($data);		
		
		$eventDataBeforeSave			= new F2cEventArgs();
		$eventDataBeforeSave->action	= 'save';
		$eventDataBeforeSave->isNew		= $isNew;		
		$eventDataBeforeSave->formNew 	=& $newFormData;
		$eventDataBeforeSave->fieldsNew	=& $fields;
		$eventDataBeforeSave->formOld	= $formOld;
		$eventDataBeforeSave->fieldsOld	= $fieldsOld;
				
		if($app->isClient('site') && $eventDataBeforeSave->fieldsOld != null)
		{
			foreach($eventDataBeforeSave->fieldsOld as $oldField)
			{
				// Copy the field data for fields that are not visible in the front-end,
				// so it's still available for plug-ins.
				if(!$oldField->frontvisible)
				{
					$fields[$oldField->fieldname] = $oldField;
				}
			}
		}
		
		// Trigger the onContentBeforeSave event.
		$result = $dispatcher->trigger($this->event_before_save, array($eventDataBeforeSave));
		
		if (in_array(false, $result, true)) 
		{
			$this->setError($eventDataBeforeSave->getError());
			return false;
		}
		
		// Merge the changed data passed to the event back into the data structure
		$this->mergeObjectWithArray($newFormData, $data);
		
		unset($data['reference_id']);
		unset($data['catAlias']);
		unset($data['catTitle']);

		if($isNew)
		{
			$data['reference_id'] = 0;
		}
		
		try
		{
			// Bind the data.
			if (!$table->bind($data)) 
			{
				$this->setError($table->getError());
				return false;
			}

			// Prepare the row for saving
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check()) 
			{
				$this->setError($table->getError());
				return false;
			}
			
			// Store the data.
			if (!$table->store()) 
			{
				$this->setError($table->getError());
				return false;
			}

			// Clean the cache.
			$cache = JFactory::getCache($this->option);
			$cache->clean();
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
		
		$pkName = $table->getKeyName();

		if (isset($table->$pkName))
		{
			$this->setState($this->getName().'.id', $table->$pkName);
		}
		
		$this->setState($this->getName().'.new', $isNew);
		
		// sync id for new forms
		$formIdNew = $this->getState('form.id');
		
		if($task == 'save2copy')
		{
			$item = $this->getItem($jinput->getInt('id'));
			
			if(count($contentType->fields))
			{
				foreach($contentType->fields as $field)
				{
					$field->copy($jinput->getInt('id'));
				}
			}
		}
		
		$this->storeFields($contentType->fields, $formIdNew, $isCron);
				
		if(!$this->parse($table->state, $isNew))
		{
			return false;
		}
		
		$formNew = $this->getItem($formIdNew);
		
		if($this->createJoomlaArticle($contentType) && array_key_exists('associations', $data))
		{
			if(!$this->handleArticleAssociations($formNew->reference_id, $formNew->language, $data['associations']));
		}
				
		$eventDataAfterSave 					= new F2cEventArgs();
		$eventDataAfterSave->action				= $eventDataBeforeSave->action;
		$eventDataAfterSave->isNew				= $eventDataBeforeSave->isNew;
		$eventDataAfterSave->formNew 			= $formNew;
		$eventDataAfterSave->fieldsNew 			= $formNew->fields;
		$eventDataAfterSave->formOld			= $eventDataBeforeSave->formOld;
		$eventDataAfterSave->fieldsOld			= $eventDataBeforeSave->fieldsOld;
		$eventDataAfterSave->parsedIntroContent	= $this->parsedIntroContent;
		$eventDataAfterSave->parsedMainContent	= $this->parsedMainContent;

		// Trigger the onContentAfterSave event.
		$result = $dispatcher->trigger($this->event_after_save, array($eventDataAfterSave));
		
		if (in_array(false, $result, true)) 
		{
			$this->setError($eventDataAfterSave->getError());
			return false;
		}

		return true;		
	}
	
	public function delete(&$pks, $batchImportMode = false)
	{
		// Initialise variables.
		$dispatcher		= JDispatcher::getInstance();
		$pks			= (array)$pks;
		$f2cFormTable 	= $this->getTable();

		// Include the content plugins for the events.				
		JPluginHelper::importPlugin('form2content');
		
		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk) 
		{
			if($f2cFormTable->load($pk)) 
			{
				if($this->canDelete($f2cFormTable) || $batchImportMode)
				{
					$formOld				= $this->getItem($pk);
					$eventData				= new F2cEventArgs();
					$eventData->action		= 'delete';
					$eventData->isNew		= false;		
					$eventData->formOld 	= $formOld;
					$eventData->fieldsOld	= $formOld->fields;
					$eventData->formNew 	= null; // no form after delete
					$eventData->fieldsNew	= null; // no fields after delete
					
					$result = $dispatcher->trigger($this->event_before_delete, array($eventData));
					
					if (in_array(false, $result, true)) 
					{
						$this->setError($eventData->getError());
						return false;
					}
															
					if($f2cFormTable->reference_id)
					{
						// Delete the Joomla article referenced by the F2C Article
						if(!self::deleteJoomlaArticle($f2cFormTable->reference_id))
						{
							return false;
						}
					}
					
					foreach($formOld->fields as $field)
					{
						$field->deleteArticle($pk);
					}
					
					// Delete the field contents	
					$query = $this->_db->getQuery(true);
					
					$query->delete()->from('#__f2c_fieldcontent')->where('formid='.(int)$pk);
					
					$this->_db->setQuery($query);
					
					if(!$this->_db->execute())
					{
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
						
					// Delete the form			
					if (!$f2cFormTable->delete($pk)) 
					{
						$this->setError($f2cFormTable->getError());
						return false;
					}
					
					$result = $dispatcher->trigger($this->event_after_delete, array($eventData));
					
					if (in_array(false, $result, true)) 
					{
						$this->setError($eventData->getError());
						return false;
					}
				}
				else
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					$error = $this->getError();
					
					if ($error) 
					{
						throw new Exception($error);
					}
					else 
					{
						throw new Exception(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
					}
				}			
			}
			else 
			{
				$this->setError($f2cFormTable->getError());
				return false;
			}
		}

		// Clear the component's cache
		$cache = JFactory::getCache($this->option);
		$cache->clean();

		return true;
	}
	
	private function deleteJoomlaArticle($id)
	{
		JPluginHelper::importPlugin('content');
		
		$dispatcher	= JDispatcher::getInstance();
		$table		= JTable::getInstance('Content');
		$context 	= 'com_content.article';

		if(!$table->load($id))
		{
			// no corresponding Joomla article (anymore)
			return true;
		}
		
		// Trigger the onContentBeforeDelete event.
		$result = $dispatcher->trigger('onContentBeforeDelete', array($context, $table));
		
		if (in_array(false, $result, true)) 
		{
			$this->setError($table->getError());
			return false;
		}

		if (!$table->delete($id)) 
		{
			$this->setError($table->getError());
			return false;
		}

		// Trigger the onContentAfterDelete event.
		$dispatcher->trigger('onContentAfterDelete', array($context, $table));
		
		return true;
	}
	
	public function publish(&$pks, $value = 1, $batchImportMode = false)
	{
		// Initialise variables.
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();
		$table		= $this->getTable();
		$pks		= (array)$pks;
		
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
						JFactory::getApplication()->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'notice');
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
						JFactory::getApplication()->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'notice');
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
				return false;	
			}
		}
		
		JPluginHelper::importPlugin('form2content');

		// Trigger the onF2cContentChangeState event.
		$result = $dispatcher->trigger($this->event_change_state, array('com_form2content.article', $pks, $value));
		
		if (in_array(false, $result, true)) 
		{
			//$this->setError($eventDataBeforeSave->getError());
			return false;
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
					return false;
				}
			}
		}
				
		// Clear the F2C and content cache
		$cache = JFactory::getCache($this->option);
		$cache->clean();
		$cache = JFactory::getCache('com_content');
		$cache->clean();

		return true;
	}

	public function copy(&$pks, $categoryId = null)
	{
		$dispatcher = JDispatcher::getInstance();
		$dateNow 	= JFactory::getDate();
		$timestamp 	= $dateNow->toSql();
		$data 		= array();
		$newIds		= array();
		$i 			= 0;
		
		JPluginHelper::importPlugin('form2content');
		
		// Attempt to copy the forms.
		foreach ($pks as $i => $pk) 
		{
			$this->setState($this->getName().'.id', $pk);
			
			// load the form data
			$form = $this->getItem();

			$eventData				= new F2cEventArgs();
			$eventData->action		= 'copy';
			$eventData->isNew		= true;
			$eventData->formOld		= $form;
			$eventData->fieldsOld	= $form->fields;			
			
			// convert the form to a saveable structure...
			foreach($form->fields as $field)
			{
				$field->copy($pk);
			}
						
			$formTable 	= $this->getTable();

			if(!$formTable->load($pk))
			{
				$this->setError($formTable->getError());
				return false;
			}
			
			if(empty($categoryId))
			{
				$categoryId = $formTable->catid;
			}
			
			$formTable->catid = $categoryId;
			
			// Add the category title and alias
			$query = $this->_db->getQuery(true);
			
			$query->select('title, alias')->from('`#__categories`')->where('id = '.(int)$formTable->catid);
			
			$this->_db->setQuery($query);

			if($category = $this->_db->loadObject())
			{
				$formTable->catTitle = $category->title;
				$formTable->catAlias = $category->alias;
			}
			else
			{
				$formTable->catTitle = '';
				$formTable->catAlias = '';
			}
			
			// Alter the title & alias
			$titleAlias = $this->generateNewTitle($categoryId, $formTable->alias, $formTable->title);
			
			$formTable->title = $titleAlias['0'];
			$formTable->alias = $titleAlias['1'];			
			$formTable->id = 0; // force insert
			$formTable->created = $timestamp;
			$formTable->modified = null;
			$formTable->state = 0; // default unpublished
			$formTable->reference_id = null; // no article yet
			
			$eventData->formNew 	=& $formTable;
			$eventData->fieldsNew	=& $form->fields;
			
			$result = $dispatcher->trigger($this->event_before_save, array(&$eventData));

			if (in_array(false, $result, true)) 
			{
				$this->setError($eventData->getError());
				return false;
			}
			
			// Perform a check, because this will create the title alias
			if(!$formTable->check())
			{
				$this->setError($formTable->getError());
				return false;
			}
			
			// remove helper items that are nog part of the table
			unset($formTable->catTitle);
			unset($formTable->catAlias);
			
			if(!$formTable->store())
			{
				$this->setError($formTable->getError());
				return false;
			}

			$this->storeFields($form->fields, $formTable->id);
			
			// Sync stored data with model
			$this->setState($this->getName().'.id', $formTable->id);
			
			// reload the form data
			$formNew				= $this->getItem();
			$eventData->formNew 	= $formNew;
			$eventData->fieldsNew 	= $formNew->fields;
			
			$result = $dispatcher->trigger($this->event_after_save, array($eventData));
			
			if (in_array(false, $result, true)) 
			{
				$this->setError($eventData->getError());
				return false;
			}

			// Add the new ID to the array
			$newIds[$i]	= $formTable->id;
			$i++;		
		}

		return $newIds;
	}
	
	protected function validateFields($data, $fields)
	{
		$isNew 	= empty($data['id']);
		$item 	= $this->getItem(($isNew ? 0 : (int)$data['id']));
		
		// Check required fields		
		foreach($fields as $field)
		{
			try
			{
				$field->validate($data, $item);
			}
			catch(Exception $exc)
			{
				JLog::add('Validation failed for field ' . $field->fieldname, JLog::INFO, 'com_form2content');
				
				$this->setError($exc->getMessage());
				return false;
			}
		}

		return true;		
	} 

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_form2content.edit.form.data', array());

		if (empty($data)) 
		{
			$data 					= $this->getItem();		
			/*	
			$contentTypeId 			= $data->projectid;						
			$contentType			= F2cFactory::getContentType($contentTypeId);
			
			// Check if a default category is selected
			$categoryField = $contentType->getFieldByType('JoomlaCategory');
			
			$defaultCategoryId = (int)$categoryField->settings->get('default');
	
			if($defaultCategoryId != -1)
			{
				if((int)$categoryField->settings->get('behaviour') == 0)
				{
					// The category is fixed
					$data->set('catid', $defaultCategoryId);
				}
			}
			*/
		}
		else 
		{
			// convert data to JObject
			$tmpData = new JObject();
			
			foreach($data as $key => $value)
			{
				$tmpData->$key = $value;
			}
			
			$data = $tmpData;
		}		
		
		// Set the item to the cachedItem to prevent it from loading again
		$this->cachedItem = $data;

		return $data;
	}
		
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if($user->authorise('core.delete', 'com_form2content.form.'.(int)$record->id))
		{
			return true;
		}
		
		if($user->authorise('form2content.delete.own', 'com_form2content.form.'.(int)$record->id))
		{
			// Now test the owner is the user.
			$ownerId 	= (int)isset($record->created_by) ? $record->created_by : 0;
			if (empty($ownerId) && $record) 
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($record->id);

				if (empty($record)) 
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $user->id) 
			{
				return true;
			}			
		}
		
		return false;
	}

	protected function canEditState($record)
	{
		$user 	= JFactory::getUser();
		$asset 	= $this->option;
		
		// Check for existing article.
		if(empty($record->id))
		{
			// this is a new article, test if the user has edit (own) state
			$asset .= '.project.' . (int)$record->projectid;
			return $user->authorise('core.edit.state', $asset) || $user->authorise('form2content.edit.state.own', $asset);
		}
		else
		{
			// existing article
			$asset .= '.form.' . (int)$record->id;
			
			if($user->authorise('core.edit.state', $asset))
			{
				return true;
			}
			
			if($user->authorise('form2content.edit.state.own', $asset))
			{
				// Now test the owner is the user.
				$ownerId	= (int)isset($record->created_by) ? $record->created_by : 0;
				if (empty($ownerId) && $record) 
				{
					// Need to do a lookup from the model.
					$record	= $this->getItem($record->id);

					if (empty($record)) 
					{
						return false;
					}
	
					$ownerId = $record->created_by;
				}
	
				// If the owner matches 'me' then do the test.
				if ($ownerId == $user->id) 
				{
					return true;
				}			
			}			
			
		}
		
		return false;
	}

	private function canTrash($record)
	{
		$user = JFactory::getUser();

		if($user->authorise('form2content.trash', 'com_form2content.form.'.(int) $record->id))
		{
			return true;
		}
			
		if($user->authorise('form2content.trash.own', 'com_form2content.form.'.(int) $record->id))
		{
			// Now test the owner is the user.
			$ownerId	= (int)isset($record->created_by) ? $record->created_by : 0;
			if (empty($ownerId) && $record) 
			{
				// Need to do a lookup from the model.
				$record		= $this->getModel()->getItem($record->id);

				if (empty($record)) 
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $user->id) 
			{
				return true;
			}			
		}

		return false;
	}
	
	protected function prepareTable($table)
	{
		// Set the publish date to now
		if($table->state == 1 && intval($table->publish_up) == 0) 
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		// Reorder the articles within the category so the new article is first
		if (empty($table->id)) 
		{
			$table->reorder('catid = '.(int) $table->catid.' AND state >= 0');
		}
	}
	
	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}
	
	public function getContentTypeSelectList($publishedOnly = true, $authorizedOnly = true)
	{
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$user 	= JFactory::getUser();
		
		$query->select('id AS value, title AS text');
		$query->from('`#__f2c_project`');				
		if($publishedOnly) $query->where('published = 1');
		$query->order('title'); 
		$db->setQuery($query->__toString());
		
		$contentTypeList = $db->loadObjectList();
		
		foreach($contentTypeList as $i => $contentType)
		{
			if ($user->authorise('core.create', 'com_form2content.project.'.$contentType->value) != true ) 
			{
				unset($contentTypeList[$i]);
			}
		}
		
		return $contentTypeList;			
	}
	
	public function parse($publishState, $isNew = false)
	{
		JPluginHelper::importPlugin('form2content');
		
		$dispatcher 		= JDispatcher::getInstance();
		$context			= $this->option.'.'.$this->name;	
		$form 				= $this->getItem();

		$eventData					= new F2cEventArgs();
		$eventData->action			= 'parse';
		$eventData->isNew			= $isNew;
		$eventData->formOld			= $form;
		$eventData->fieldsOld		= $form->fields;			
		$eventData->formNew			= clone $form;
		$eventData->fieldsNew		= $eventData->formNew->fields;			
		$eventData->formNew->state	= $publishState;
		$performParse 				= true;

		$result = $dispatcher->trigger($this->event_before_parse, array($eventData));
		
		if (in_array(false, $result, true)) 
		{
			$this->setError($eventData->getError());
			return false;
		}
		
		if($performParse)
		{	
			// Resync data
			$form 				= $this->getItem();
			$translatedFields 	= array();

			// Fill the translations for the fields
			if(count($form->fields))
			{
				foreach($form->fields as $contentTypeField)
				{
					if(array_key_exists($contentTypeField->id, $translatedFields))
					{
						$translatedField = $translatedFields[$contentTypeField->id];
						
						if($translatedField->title_translation)
						{
							$contentTypeField->title = $translatedField->title_translation;
						}

						if($translatedField->description_translation)
						{
							$contentTypeField->description = $translatedField->description_translation;
						}						
					}
				}
			}

			if(!$this->parseSingleForm($form, $form->fields, $publishState))
			{
				return false;
			}
			
			$eventData->parsedIntroContent = $this->parsedIntroContent;
			$eventData->parsedMainContent = $this->parsedMainContent;
			
			$result = $dispatcher->trigger($this->event_after_parse, array($eventData));
			
			if (in_array(false, $result, true)) 
			{
				$this->setError($eventData->getError());
				return false;
			}
		}
				
		return true;
	}
	
	public function parseSingleForm($form, $formData, $publishState)
	{
		$translate 		= $this->f2cConfig->get('custom_translations', false);
		$user 			= JFactory::getUser();
		$nullDate 		= $this->_db->getNullDate();
		$datenow 		= JFactory::getDate();
		$dispatcher 	= JDispatcher::getInstance();
		$row			= null;
		$errorMsgPrefix	= JText::_('COM_FORM2CONTENT_FORM_ID') . ' ' . $form->id . ': ';
		$parseResult	= new JObject();
		
		JPluginHelper::importPlugin('content');
		
		$parser = new F2cParser();

		$introTemplate = $form->intro_template;
		
		if(!$introTemplate)
		{ 
			// user has not specified an intro template -> generate it dynamically
			$introTemplate = 'string:'.$this->generateDefaultIntroTemplate($form->projectid);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_FORM2CONTENT_WARNING_DEFAULT_TEMPLATE_SELECTED'), 'warning');
		}
		
		if(!$parser->addTemplate($introTemplate, F2C_TEMPLATE_INTRO))
		{
			$this->setError($errorMsgPrefix . $parser->getError());
			return false;				
		}
		
		if($form->main_template)
		{
			if(!$parser->addTemplate($form->main_template, F2C_TEMPLATE_MAIN))
			{
				$this->setError($errorMsgPrefix . $parser->getError());
				return false;				
			}
		}
	
		// Load the custom translations
		if($translate)
		{
			$lang 	= JFactory::getLanguage();
			$tag 	= ($form->language == '*') ? $lang->getTag() : $form->language;
			
			$lang->load('com_form2content_custom', JPATH_SITE, $tag);
		}
		
		$categoryAlias	= $form->catAlias; // for use in re-parsing
		$joomlaId 		= $form->reference_id;
		$usrTmp 		= JFactory::getUser($form->created_by);
		$form->fields 	= $formData;
		
		$parser->addVars($form);
		
		$row = $this->getTable('content');
		$row->load((int)$joomlaId);
		$isNew = false;
		
		$query = $this->_db->getQuery(true);

		$query->select('count(*)');
		$query->from('`#__content`');				
		$query->where('id=' . (int)$joomlaId);
		$this->_db->setQuery($query);
		
		if($this->_db->loadResult())
		{
			$isNew = false;			
			// fail if checked out not by 'me'
			if ($row->checked_out && $row->checked_out != $user->id) 
			{
				$this->setError($errorMsgPrefix . JText::_('COM_FORM2CONTENT_ERROR_FORM_CHECKED_OUT'));
				return false;
			}
			
			$row->modified 		= $datenow->toSql();
			$row->modified_by 	= $user->get('id');			
		}
		else
		{
			// deleted / archived
			$isNew = true;
			$joomlaId 			= null;
			$form->reference_id = null;
		}
		
		if($isNew)
		{
			// init new content item
			$row = $this->getTable('content');
			$row->load(0);
			// if $publishState is not set, set item to form publish state
			$row->state		= ($publishState == F2C_STATE_RETAIN) ? F2C_STATE_PUBLISHED : $form->state;
			$row->state		= $form->state;
			$row->modified 	= $nullDate;
		}
		
		$row->created_by 	= $usrTmp->id;
		$row->created 		= $form->created;
		
		if($form->publish_up == $nullDate)
		{	
			$row->publish_up = $datenow->format('Y-m-d') . ' 00:00:00';
		}	
		else
		{
			$row->publish_up = $form->publish_up;
		}
		
		$row->publish_down = $form->publish_down;
			
		if($this->f2cConfig->get('autosync_article_order'))
		{
			$row->ordering = $form->ordering;
		}
	
		$this->parsedIntroContent = $parser->parseIntro();
		
		if($parser->getError())
		{
			$this->setError($errorMsgPrefix . $parser->getError());
			return false;
		}
		
		$this->parsedMainContent = $parser->parseMain();
		
		if($parser->getError())
		{
			$this->setError($errorMsgPrefix . $parser->getError());
			return false;
		}
	
		$attribs = new Registry();
		$attribs->loadArray($form->attribs);
		
		$metadata = new Registry();
		$metadata->loadArray($form->metadata);
		
		// Handle the images and links
		$contentType			= F2cFactory::getContentType($form->projectid);
		$imageSettings 			= new Registry($contentType->images);
		$urlSettings 			= new Registry($contentType->urls);
		$images 				= new Registry();
		$urls 					= new Registry();

		$this->handleArticleImage($imageSettings->get('image_intro'), $form, $formData, 'intro', $images, $imageSettings);
		$this->handleArticleImage($imageSettings->get('image_fulltext'), $form, $formData, 'fulltext', $images, $imageSettings);
		$this->handleArticleUrl($urlSettings->get('urla'), $formData, 'a', $urls, $urlSettings);
		$this->handleArticleUrl($urlSettings->get('urlb'), $formData, 'b', $urls, $urlSettings);
		$this->handleArticleUrl($urlSettings->get('urlc'), $formData, 'c', $urls, $urlSettings);
		
		$row->introtext 		= $this->parsedIntroContent;	
		$row->fulltext 			= $this->parsedMainContent;
		$row->title 			= $form->title;
		$row->alias 			= $form->alias;
		$row->metadesc 			= $form->metadesc;
		$row->metakey			= $form->metakey;
		$row->catid 			= $form->catid;
		$row->state 			= ($publishState == F2C_STATE_RETAIN) ? $form->state : $publishState;
		$row->featured			= $form->featured;
		$row->created_by_alias 	= $form->created_by_alias;
		$row->access			= $form->access;
		$row->attribs			= $attribs->toString();
		$row->metadata			= $metadata->toString();
		$row->language			= $form->language;
		$row->images			= $images->toString();
		$row->urls	 			= $urls->toString();
		
		$newTags = false;
		
		if(isset($form->tags) && is_array($form->tags) && !empty($form->tags[0]))
		{
			// Add the tags
			$row->newTags = array_unique($form->tags);
			
			// Check if new tags were added
			foreach($form->tags as $tagId)
			{
				if(!is_numeric($tagId))
				{
					$newTags = true;
				}
			}
		}
				
		// Make sure the data is valid
		if (!$row->check()) 
		{
			$this->setError($errorMsgPrefix . $row->getError());
			return false;
		}

		// Increment the content version number
		$row->version++;

		// Trigger the onContentBeforeSave event.
		$result = $dispatcher->trigger('onContentBeforeSave', array('com_content.article', &$row, $isNew));
		
		if (in_array(false, $result, true)) 
		{
			$this->setError($errorMsgPrefix . $row->getError());
			return false;
		}
			
		// Store the content to the database
		if (!$row->store()) 
		{
			$this->setError($errorMsgPrefix . $row->getError());
			return false;
		}
	
		if($isNew || $joomlaId != $row->id || !$form->alias || $newTags)
		{
			// reparse to get the correct Article Id and/or title alias or new tags
			$joomlaId 	= $row->id;			
			$slug 		= ($row->alias) ? $joomlaId.':'.$row->alias : $joomlaId;
			$catslug 	= ($categoryAlias) ? $form->catid.':'.$categoryAlias : $form->catid;
			$seflink 	= 'index.php?option=com_content&view=article&id='. $slug . '&catid=' . $catslug;
						
			$parser->clearVar('JOOMLA_ID');
			$parser->addVar('JOOMLA_ID', $row->id);
			$parser->clearVar('JOOMLA_TITLE_ALIAS');
			$parser->addVar('JOOMLA_TITLE_ALIAS', $row->alias);
			$parser->clearVar('JOOMLA_ARTICLE_LINK');		
			$parser->addVar('JOOMLA_ARTICLE_LINK', $seflink);
			$parser->clearVar('JOOMLA_ARTICLE_LINK_SEF');
			$parser->addVar('JOOMLA_ARTICLE_LINK_SEF', '{plgContentF2cSef}'.$slug.','.$catslug.'{/plgContentF2cSef}');	

			$this->reparseTags($row->id, $parser);
			
			$parser->clearTemplates();
	
			$this->parsedIntroContent = $parser->parseIntro();
			
			if($parser->getError())
			{
				$this->setError($errorMsgPrefix . $parser->getError());
				return false;
			}
		
			$this->parsedMainContent = $parser->parseMain();
			
			if($parser->getError())
			{
				$this->setError($errorMsgPrefix . $parser->getError());
				return false;
			}
	
			$row->introtext	= $this->parsedIntroContent;
			$row->fulltext 	= $this->parsedMainContent;
				
			// Make sure the data is valid
			if (!$row->check()) 
			{
				$this->setError($errorMsgPrefix . $row->getError());
				return false;
			}
	
			// Store the content to the database
			if (!$row->store()) 
			{
				$this->setError($errorMsgPrefix . $row->getError());
				return false;
			}
		}
	
		$row->checkin();
		
		if($this->f2cConfig->get('autosync_article_order'))
		{
			F2cContentHelper::syncArticleOrder($row->catid);	
		}
		
		// Set the featured option of the Joomla article
		$this->featured($joomlaId, $form->featured);
				
		// Clean the cache.
		$cache = JFactory::getCache($this->option);
		$cache->clean();

		// Trigger the onContentAfterSave event.
		$result = $dispatcher->trigger('onContentAfterSave', array('com_content.article', &$row, $isNew));
		
		if (in_array(false, $result, true)) 
		{
			$this->setError($errorMsgPrefix . $row->getError());
			return false;
		}
				
	 	// Sync F2C Article with data from Joomla Article
	 	$rowForm = $this->getTable('form');
	 	$rowForm->load($form->id);
	 	$rowForm->alias = $row->alias;
	 	$rowForm->state = $row->state;
		$rowForm->reference_id = $row->id;
		
		// Sync tags
		$tagsHelper = new JHelperTags;
		$tagsHelper->getTagIds($row->id, 'com_content.article');
		$extended = new JRegistry();
		$extended->set('tags', $tagsHelper->tags);
		$rowForm->extended = $extended->toString();
		
		if (!$rowForm->store()) 
		{
			$this->setError($errorMsgPrefix . $rowForm->getError());
			return false;
		}
		
		return true;
	}
	
	/*
	 * Toggle the featured option of a Joomla Article
	 */
	public function featured($pk, $value = 0)
	{
		JTable::addIncludePath(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'tables');
		
		$table = $this->getTable('Featured', 'ContentTable');

		try 
		{
			$db = $this->getDbo();

			// Adjust the mapping table.
			// Clear the existing features settings.
			$query = $db->getQuery(true);
			$query->delete('#__content_frontpage');
			$query->where('content_id = ' . (int)$pk);
					
			$db->setQuery($query);

			if (!$db->execute()) 
			{
				throw new Exception($db->getErrorMsg());
			}

			if($value == 1) 
			{
				// Featuring.
				$query = $db->getQuery(true);
				$query->insert('#__content_frontpage');
				$query->set('content_id='.(int)$pk);
				$query->set('ordering=1');
				
				$db->setQuery($query);

				if (!$db->execute()) 
				{
					$this->setError($db->getErrorMsg());
					return false;
				}
			}
		} 
		catch (Exception $e) 
		{
			$this->setError($e->getMessage());
			return false;
		}

		$table->reorder();

		$cache = JFactory::getCache('com_content');
		$cache->clean();

		return true;
	}
	
	public function featuredList($pks, $value)
	{
		// Initialise variables.
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();
		$table		= $this->getTable();
		$pks		= (array)$pks;
		
		JArrayHelper::toInteger($pks);

		if (empty($pks)) 
		{
			$this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
			return false;
		}

		// Include the content plugins for the change of state event.
		JPluginHelper::importPlugin('content');

		// Access checks.
		foreach ($pks as $i => $pk) 
		{
			$table->reset();

			if ($table->load($pk)) 
			{
				if (!$this->canEditState($table)) 
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					JFactory::getApplication()->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'notice');
				}
				
				$table->featured = $value;
				
				if(!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}
				
				// Set the correct PK for the model
				$this->setState($this->getName().'.id', $pk);
				
				if(!$this->parse($table->state, false))
				{
					return false;	
				}				
			}
		}
		
		// Clear the F2C and content cache
		$cache = JFactory::getCache($this->option);
		$cache->clean();
		$cache = JFactory::getCache('com_content');
		$cache->clean();

		return true;
	}
	
	public function reorder($pks, $delta = 0)
	{
		// reorder the f2c_form table
		if(parent::reorder($pks, $delta))
		{
			if($this->f2cConfig->get('autosync_article_order'))
			{
				$categories = $this->getCategories($pks);
				
				foreach($categories as $catid)
				{
					F2cContentHelper::syncArticleOrder($catid);						
				}				
			}			
		}
		else
		{
			return false;
		}	
	}
	
	public function saveorder($pks = null, $order = null)
	{
		// reorder the f2c_form table
		if(parent::saveorder($pks, $order))
		{
			if($this->f2cConfig->get('autosync_article_order'))
			{
				$categories = $this->getCategories($pks);
				
				foreach($categories as $catid)
				{
					F2cContentHelper::syncArticleOrder($catid);						
				}
			}			
		}
		else
		{
			return false;
		}
	}
	
	/*
	 * Load a list of unique category Id's for a set of F2C Articles
	 */
	private function getCategories($pks)
	{
		$db = $this->getDbo();
	
		$query = $db->getQuery(true);
		$query->select('DISTINCT catid');
		$query->from('#__f2c_form');
		$query->where('id IN ('. implode(',', $pks) . ')');

		$db->setQuery($query);
		return $db->loadColumn();		
	}
	
	/**
	 * Method to validate the form data.
	 *
	 * @param	object		$form		The form to validate against.
	 * @param	array		$data		The data to validate.
	 * @return	mixed		Array of filtered data if valid, false otherwise.
	 * @since	4.0.0
	 */
	function validate($form, $data, $group = null)
	{
		$app 	= JFactory::getApplication();
		$item 	= $this->getItem($data['id']);
		$isNew	= empty($data['id']);
		
		// override the required setting for fields that are not shown on the form
		if($app->isClient('site'))
		{
			$contentTypeId 	= (int)$data['projectid'];
			$contentType	= F2cFactory::getContentType($contentTypeId);

			foreach($contentType->fields as $field)
			{
				if(!$field->frontvisible)
				{
					$field->prepareValidation($item, $data, $isNew);
				}	
			}

			$titleField = $contentType->getFieldByType('JoomlaTitle');
	
			if(!$titleField->frontvisible)
			{
				$data['title'] = $isNew ? $titleField->settings->get('default') : $item->title;
			}
		}
		
		$validData = parent::validate($form, $data);
			
		if(!$this->canEditState($item))
		{
			// retain the current state values
			$validData['featured'] 		= $item->featured;
			$validData['ordering'] 		= $item->ordering;
			$validData['publish_up'] 	= $item->publish_up;
			$validData['publish_down'] 	= $item->publish_down != JFactory::getDbo()->getNullDate() ? $item->publish_down : '';
			$validData['state'] 		= $item->state;			
		}

		return $validData;
	}
		
	private function convertArrayToObject($array)
	{
		$object = new JObject();
		
		foreach($array as $key => $value)
		{
			$object->$key = $value;
		}
		
		return $object;
	}
	
	private function mergeObjectWithArray($object, &$array)
	{
		foreach(get_object_vars($object) as $property => $value)
		{
			// Skip properties starting with underscore
			if(strpos($property, '_') !== 0)
			{
				// overwrite the array value
				$array[$property] = $value;
			}
		}
	}
	
	private function getCategoryInfo($catId)
	{
		$query = $this->_db->getQuery(true);
		
		$query->select('title, alias');
		$query->from('`#__categories`');		
		$query->where('id = '.(int)$catId);
		
		$this->_db->setQuery($query);

		return $this->_db->loadObject();		
	}
	
	public function createFormDataObjects($fieldContentList)
	{
		$formData  	= null;
		$fldData 	= null;
		$forms		= array();

		if(count($fieldContentList))
		{
			foreach($fieldContentList as $fieldContent)
			{
				if(!array_key_exists($fieldContent->formid, $forms))
				{
					$forms[$fieldContent->formid] = array();
				}
				
				$formData =& $forms[$fieldContent->formid];

				if(!array_key_exists($fieldContent->fieldname, $formData))
				{	
					// Dynamically create F2C field
					$className 							= 'F2cField'.$fieldContent->name;
					$fldData 							= new $className($fieldContent);
					$formData[$fieldContent->fieldname] = $fldData;
				}
				
				$fldData->setData($fieldContent);
			}
		}

		return $forms;		
	}
	
	public function importXml($xmlLoadResult)
	{
		jimport('joomla.log.log');
		
		$namespace			= 'http://schemas.form2content.com/forms';
		$f2cConfig 			= F2cFactory::getConfig();
		$nullDate			= $this->_db->getNullDate();
		$importWriteMode 	= $f2cConfig->get('import_write_action', 1);
		$results			= array('ins' => 0, 'upd' => 0, 'del' => 0, 'res' => 0);

		// Always create new articles for F2C Lite
		$importWriteMode = F2C_IMPORT_WRITEMODE_CREATENEW;
			
		$xml 										= $xmlLoadResult[1];		
		list($xsdMajor, $xsdMinor, $xsdRevision) 	= explode('.', $xmlLoadResult[0]);
		
		if($xsdMajor < 2)
		{
			// perform the legacy import
			$legacyForm = new F2cLegacyForm();
			$result = $legacyForm->import($xml, $results);
			
			if($result !== true)
			{
				$this->setError($result);
			}
			
			return $results;
		}
		
		foreach ($xml as $xmlForm)
		{			
			$form 			= new Form2ContentModelForm(array('ignore_request' => true));
			$attribs 		= array();
			$metadata		= array();
			$rules			= array();
			$data 			= array();
			$fieldData 		= array();
			$tmpFiles		= array();

			$xmlForm->registerXPathNamespace('f', $namespace);
			
			// Resolve the Content Type
			$contentTypeId = $this->dicContentTypeTitle[(string)$xmlForm->contenttype];

			if(!$contentTypeId)
			{
				$this->setError(sprintf(JText::_('COM_FORM2CONTENT_ERROR_CONTENTTYPE_NOT_RESOLVED'), (string)$xmlForm->contenttype));
				return $results;
			}
			
			$contentType = F2cFactory::getContentType($contentTypeId);
			
			if($importWriteMode == F2C_IMPORT_WRITEMODE_CREATENEW)
			{
				// Always create a new F2C Article
				$formId = 0;
			}
			else 
			{
				// Check if an alternate key was specified
				if($xmlForm->id->attributes()->fieldname)
				{
					// Find the id of the form through the alternate key
					$query = $this->_db->getQuery(true);
					
					$query->select('frm.id');
					$query->from('#__f2c_form frm');
					$query->join('inner', '#__f2c_fieldcontent flc on frm.id = flc.formid');
					$query->join('inner', '#__f2c_projectfields pfl on flc.fieldid = pfl.id');
					$query->where('flc.content = ' . $this->_db->quote((string)$xmlForm->id));						
					$query->where('pfl.fieldname = ' . $this->_db->quote((string)$xmlForm->id->attributes()->fieldname));
					$query->where('frm.state in (0,1)');

					$this->_db->setQuery($query);
					$result = $this->_db->loadResult();
					// If the alternate key was found use that form id						
					$formId = $result ? $result : 0;
				}
				else 
				{
					// verify that this article exists for the specified content type
					$formTemp = $this->getItem((int)$xmlForm->id);
					
					if($formTemp->projectid != $contentTypeId)
					{
						// there's no such form...create a new one
						$formId = 0;
					}
					else 
					{
						$formId = (int)$xmlForm->id;
					}
				}
			}

			// Check the state of the article, find the state field
			$result = $xmlForm->xpath('.//f:field[f:fieldname="published"]');
			
			if(!count($result))
			{
				// TODO Set message
				$this->setError(JText::_('COM_FORM2CONTENT_ERROR_F2C_STATE_FIELD_MISSING'));
				return $results;
			}
			
			$state = $result[0]->contentSingleTextValue->value;

			switch($state)
			{
				case 'deleted':
					$pks = array((int)$xmlForm->id);
					$this->delete($pks, true);
					$results['del'] += 1;
					break;
				case 'trashed':
					// Trash the article
					$pks = array((int)$xmlForm->id);
					$form->publish($pks, F2C_STATE_TRASH, true);
					$results['del'] += 1;
					break;
			}
			
			if($state == 'trashed' || $state == 'deleted')
			{
				// stop further execution for this form
				continue;	
			}
			
			if(count($xmlForm->attribs->children()))
			{
				foreach ($xmlForm->attribs->children() as $attribName => $xmlAttrib)
				{
					$attribs[$attribName] = (string)$xmlAttrib;
				}
			}
			
			if(count($xmlForm->metadata->children()))
			{
				foreach ($xmlForm->metadata->children() as $metadataName => $xmlMetadata)
				{
					$metadata[$metadataName] = (string)$xmlMetadata;
				}
			}

			// Load the existing form
			$existingForm 		= $form->getItem($formId);
			$existingFieldData 	= $existingForm->fields;
			
			$data['id'] 				= $formId;
			$data['projectid'] 			= $contentTypeId;
			$data['attribs'] 			= $attribs;
			$data['metadata'] 			= $metadata;

			foreach($contentType->fields as $field)
			{
				$field->reset();				
				$fieldData[$field->fieldname] = $field;
			}

			if(count($xmlForm->fields->children()))
			{
				foreach($xmlForm->fields->children() as $xmlField)
				{
					$f2cField =& $fieldData[(string)$xmlField->fieldname];
					
					if($f2cField == null)
					{
						// Field is present in import XML, but not part of Content Type to which it should be imported
						$this->setError(sprintf(JText::_('COM_FORM2CONTENT_ERROR_FIELD_NOT_PART_OF_CONTENTTYPE'), (string)$xmlField->fieldname, (string)$xmlForm->contenttype));
						return false;
					}
					
					if(array_key_exists($f2cField->fieldname, $existingForm->fields))
					{
						$existingInternalData = $existingForm->fields[$f2cField->fieldname]->internal;
					}
					else 
					{
						$existingInternalData = null;
					}
					
					try 
					{
						$f2cField->import($xmlField, $existingInternalData, $data);	
					}
					catch(Exception $e)
					{
						$this->setError('Error for article #'.$data['id'].' ('.$data['title'].'): ' . $e->getMessage());
						return $results;
					}
				}

				$data['preparedFieldData'] = $fieldData;
				$data['isCron'] = true;
				
				if(!$form->saveCron($data))
				{
					$this->setError('Error for article #'.$data['id'].' ('.$data['title'].'): ' . $form->getError());
					return $results;
				}
				
				if($formId)
				{
					$results['upd'] += 1;
				}
				else
				{
					$results['ins'] += 1;
				}
			}
		}
		
		// Import succeeded
		$results['res'] = 1;
		
		return $results;
	}
	
	/*
	 * Load the import XML file and validate it against the XML Schema file.
	 */
	public function loadImportFile($filename)
	{
		$result = array();
		$domXml = new DOMDocument();
		$domXml->load($filename);
		
	
		libxml_use_internal_errors(true);

		$schemaTag = $domXml->documentElement->getAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation');
		
		if(!$schemaTag)
		{
			$this->setError(JText::_('COM_FORM2CONTENT_ERROR_IMPORT_INVALID_XML_FILE'). ': '.$filename);
			$this->setError(JText::_('COM_FORM2CONTENT_ERROR_NO_SCHEMA_FILE'));
			return false;
		}
		
        $pairs = preg_split('/\s+/', $schemaTag);
        $pairCount = count($pairs);
        
        if ($pairCount <= 1)
        {
			$this->setError(JText::_('COM_FORM2CONTENT_ERROR_IMPORT_INVALID_XML_FILE'). ': '.$filename);
			$this->setError(JText::_('COM_FORM2CONTENT_ERROR_INVALID_SCHEMA_LOCATION'));
			return false;         
        }
		
      	$schemaLocation = JPATH_SITE.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'schemas'.DIRECTORY_SEPARATOR.$pairs[1];

      	if(!JFile::exists($schemaLocation))
      	{
			$this->setError(JText::_('COM_FORM2CONTENT_ERROR_IMPORT_INVALID_XML_FILE'). ': '.$filename);
			$this->setError(sprintf(JText::_('COM_FORM2CONTENT_ERROR_SCHEMA_NOT_FOUND'), $schemaLocation)); 
			return false;              		
      	}
      	
		if(!$domXml->schemaValidate($schemaLocation))
		{
			$this->setError(JText::_('COM_FORM2CONTENT_ERROR_IMPORT_INVALID_XML_FILE'). ': '.$filename);
			
			foreach(libxml_get_errors() as $error)
			{
				$this->setError($error->message);
			}			

			return false;
		}

		// add the version of the schema to the results so we know how to handle the document
		$versionparts 	= explode('_', str_replace('.xsd', '', $pairs[1]));		
		$result[0] 		= $versionparts[2].'.'.$versionparts[3].'.'.$versionparts[4]; 
		// We're dealing with a valid XML file, convert it for further handling to a SimpleXml document
		$result[1] = simplexml_import_dom($domXml);
		
		return $result;
	}
	
	/*
	 * Load various dictionaries to convert some values into IDs and vice versa
	 */
	public function InitXmlImport()
	{
		// Load a dictionary of Content Types
		$query = $this->_db->getQuery(true);
		
		$query->select('id, title');
		$query->from('#__f2c_project');
		
		$this->_db->setQuery($query);

		$this->dicContentTypeTitle 	= $this->_db->loadAssocList('title', 'id');
	}
	
	private function handleArticleImage($imageId, $form, $formData, $tag, $images, $imageSettings)
	{		
		// find the image
		if (count($formData))
		{
			foreach ($formData as $formField)
			{ 
				
				if($formField->id == $imageId && $formField->values['FILENAME'])
				{
					if($imageSettings->get('imagetype_'.$tag) == 'full')
					{
						$images->set('image_'.$tag, Path::Combine(F2cFieldImage::GetImagesUrl($form->projectid, $form->id, true, true), $formField->values['FILENAME']));
					}
					else
					{
						$images->set('image_'.$tag, Path::Combine(F2cFieldImage::GetThumbnailsUrl($form->projectid, $form->id, true, true), $formField->values['FILENAME']));
					}
					
					$images->set('float_'.$tag, $imageSettings->get('float_'.$tag));
					$images->set('image_'.$tag.'_alt', $formField->values['ALT'] ? $formField->values['ALT'] : $imageSettings->get('image_'.$tag.'_alt'));
					$images->set('image_'.$tag.'_caption', $formField->values['TITLE'] ? $formField->values['TITLE'] : $imageSettings->get('image_'.$tag.'_caption'));
					return true;
				}
			}
			
			// No image present
			$images->set('image_'.$tag, '');
			$images->set('float_'.$tag, '');
			$images->set('image_'.$tag.'_alt', '');
			$images->set('image_'.$tag.'_caption', '');
		}
	}
	
	private function handleArticleUrl($urlId, $formData, $tag, $urls, $urlSettings)
	{
		// find the image
		if (count($formData))
		{
			foreach ($formData as $formField)
			{
				if($formField->id == $urlId) //&& $formField->values['URL'])
				{
					$urls->set('url'.$tag, $formField->values['URL']);
					$urls->set('url'.$tag.'text', $formField->values['TITLE'] ? $formField->values['TITLE'] : $urlSettings->get('url'.$tag.'text'));
					
					$target = $formField->values['TARGET'] ? $formField->values['TARGET'] : $urlSettings->get('target'.$tag);
					
					switch($target)
					{
						case '_top':
							$target = 0;
							break;
						case '_blank':
							$target = 1;
							break;
					}
					
					$urls->set('target'.$tag, $target);
					return true;
				}
			}
			
			// No url present
			$urls->set('url'.$tag, '');
			$urls->set('url'.$tag.'text', '');
			$urls->set('target'.$tag, '');
		}
	}
	
	private function handleArticleAssociations($joomlaId, $language, $associations)
	{		
		if (JLanguageAssociations::isEnabled())
		{
			foreach ($associations as $tag => $id)
			{
				if (empty($id))
				{
					unset($associations[$tag]);
				}
			}

			// Detecting all item menus
			$all_language = $language == '*';

			if ($all_language && !empty($associations))
			{								
				JError::raiseNotice(403, JText::_('COM_CONTENT_ERROR_ALL_LANGUAGE_ASSOCIATED'));
			}

			$associations[$language] = $joomlaId;

			// Deleting old association for these items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->delete('#__associations')
				->where('context=' . $db->quote('com_content.item'))
				->where('id IN (' . implode(',', $associations) . ')');
			$db->setQuery($query);
			$db->execute();

			if ($error = $db->getErrorMsg())
			{
				$this->setError($error);
				return false;
			}

			if (!$all_language && count($associations))
			{
				// Adding new association for these items
				$key = md5(json_encode($associations));
				$query->clear()
					->insert('#__associations');

				foreach ($associations as $id)
				{
					$query->values($id . ',' . $db->quote('com_content.item') . ',' . $db->quote($key));
				}

				$db->setQuery($query);
				$db->execute();

				if ($error = $db->getErrorMsg())
				{
					$this->setError($error);
					return false;
				}
			}
		}
		
		return true;
	}
	
	protected function createJoomlaArticle($contentType)
	{
		$createJoomlaArticle = true;
		
		return $createJoomlaArticle;
	}
	
	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @see     JFormField
	 * @since   5.2.0
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{		
		if(empty($this->contentTypeId))
		{
			if(is_array($data))
			{
				$contentTypeId = (int)$data['projectid'];
			}
			else 
			{
				$contentTypeId = (int)$data->projectid;
			}
		}
		else 
		{
			$contentTypeId = $this->contentTypeId;
		}
		
		$contentType	= F2cFactory::getContentType($contentTypeId);
		$settings 		= $contentType->settings;
		$ajaxMode 		= (array_key_exists('tag_field_ajax_mode', $settings) && $settings['tag_field_ajax_mode'] != 1) ? 'nested' : 'ajax';
		$customTags		= (array_key_exists('tags_allow_custom', $settings) && $settings['tags_allow_custom'] != 1) ? 'deny' : 'allow';
		
		$form->setFieldAttribute('tags', 'mode', $ajaxMode);
		$form->setFieldAttribute('tags', 'custom', $customTags);
		
		foreach ($contentType->fields as $field)
		{
			$field->preprocessForm($form);
		}

		// Association content items
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();
		if ($assoc)
		{
			$languages = JLanguageHelper::getLanguages('lang_code');
			$addform = new SimpleXMLElement('<form />');
			$fields = $addform->addChild('fields');
			$fields->addAttribute('name', 'associations');
			$fieldset = $fields->addChild('fieldset');
			$fieldset->addAttribute('name', 'item_associations');
			$fieldset->addAttribute('description', 'COM_CONTENT_ITEM_ASSOCIATIONS_FIELDSET_DESC');
			$add = false;
			foreach ($languages as $tag => $language)
			{
				if (empty($data->language) || $tag != $data->language)
				{
					$add = true;
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $tag);
					$field->addAttribute('type', 'modal_article');
					$field->addAttribute('language', $tag);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
				}
			}
			if ($add)
			{
				$form->load($addform, false);
			}
		}
		
		parent::preprocessForm($form, $data, $group);
	}
		
	private function storeFields($fields, $formId)
	{
		$db			= JFactory::getDBO();
		$isBackEnd 	= JFactory::getApplication()->isAdmin();
				
		foreach($fields as $field)
		{
			$content = $field->store($formId);
			
			if(count($content))
			{						
				foreach($content as $fieldContent)
				{
					$query = $db->getQuery(true);
					
					if(!empty($fieldContent->action))
					{
						// Field value must be changed in the database
						switch($fieldContent->action)
						{
							case 'INSERT':
								$query->insert($db->quoteName('#__f2c_fieldcontent'))
									->columns('formid, fieldid, attribute, content')
	    							->values((int)$formId.','.(int)$field->id.','.$db->quote($fieldContent->attribute).','.$db->quote($fieldContent->content));
	    							break;
							case 'UPDATE':
								$query->update($db->quoteName('#__f2c_fieldcontent'))
										->set(array('attribute='.$db->quote($fieldContent->attribute), 'content='.$db->quote($fieldContent->content)))
										->where('id='.(int)$fieldContent->id);																					
								break;
							case 'DELETE':
								$query->delete('#__f2c_fieldcontent')
										->where('id='.(int)$fieldContent->id);
								break;
							default: 
								throw new Exception('Invalid attribute `action` for fieldContent', 100);
						}
						
						$db->setQuery($query);
						$db->execute();							
					}
				}
			}		
		}		
	}
	
	private function reparseTags($joomlaId, $parser)
	{
		$db 		= JFactory::getDbo();
		$tagsHelper = new JHelperTags;
		$tags 		= array();
		
		$parser->clearVar('F2C_TAGS');
		
		$tagsHelper->getTagIds($joomlaId, 'com_content.article');

		if($tagsHelper->tags)
		{
			$query = $db->getQuery(true);
			$query->select('id, title')->from('#__tags')->where('id IN ('.$tagsHelper->tags.')')->order('title ASC');
			$db->setQuery($query);
			$list = $db->loadAssocList();
			
			foreach($list as $tag)
			{
				$tags[$tag['id']] = $tag['title'];	
			}
		}
		
		$parser->addVar('F2C_TAGS', $tags);
	}

	private function checkMaxForms($table, $value)
	{
		return true;
	}
	
	private function generateDefaultIntroTemplate($contentTypeId)
	{	
		$template 		= '';				
		$contentType 	= F2cFactory::getContentType($contentTypeId);
		$fieldTypes 	= F2cFactory::getFieldTypesDictionary(true);
		
		foreach($contentType->fields as $field)
		{
			$fieldClassName = 'F2cFieldAdmin'.$fieldTypes[$field->fieldtypeid]->name;
			$adminField = new $fieldClassName();
			
			$templateFragment = $adminField->getTemplateSample($field->fieldname);
			
			if($templateFragment)
			{
				$template .= '<p>'.$templateFragment.'</p>';
			}
		}
		
		return $template;
	}	
}
?>