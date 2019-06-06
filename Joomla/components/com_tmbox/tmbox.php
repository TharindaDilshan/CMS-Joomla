<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helpers/tmbox.php';
// Require the base controller
$input = JFactory::getApplication()->input;
$view = $input->getCmd('view');
$task = $input->getCmd('task');
$controllerClass = 'TmboxController' . ucfirst($view);
$controllerPath = 'controllers/' . $view . '.php';
require_once($controllerPath);
if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    $controller->execute($task);
    $controller->redirect();
}