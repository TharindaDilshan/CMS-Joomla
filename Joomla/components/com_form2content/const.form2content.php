<?php
// No direct access
defined('_JEXEC') or die;

defined('F2C_STATE_RETAIN')					or define('F2C_STATE_RETAIN', 2); // Retains the current state
defined('F2C_STATE_PUBLISHED') 				or define('F2C_STATE_PUBLISHED', 1);
defined('F2C_STATE_UNPUBLISHED') 			or define('F2C_STATE_UNPUBLISHED', 0);
defined('F2C_STATE_TRASH') 					or define('F2C_STATE_TRASH', -2);

defined('F2C_DOCUMENTATION_URL')			or define('F2C_DOCUMENTATION_URL', 'http://documentation.form2content.com/');

defined('F2C_FIELD_JOOMLA_NATIVE')			or define('F2C_FIELD_JOOMLA_NATIVE', 0);
defined('F2C_FIELD_F2C_NATIVE')				or define('F2C_FIELD_F2C_NATIVE', 1);
?>