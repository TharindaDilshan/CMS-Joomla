<?php
/**
* @package OS Gallery
* @copyright 2019 OrdaSoft
* @author 2019 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com), Vladislav Prikhodko (vlados.vp1@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Set some global property
$document = JFactory::getDocument();

// require helper file
JLoader::register('osGalleryHelperSite', JPATH_COMPONENT . '/helpers/osGalleryHelperSite.php');
JLoader::register('osGallerySocialButtonsHelper', JPATH_COMPONENT . '/helpers/osGallerySocialButtonsHelper.php');

// Perform the Request task
// print_r($_REQUEST);exit;
$input = JFactory::getApplication()->input;
$task = $input->getCmd('task', '');
$view = $input->getCmd('view', '');
//var_dump($view); exit;
if(!$task && $view)$task = $view;

switch ($task) {
    case "defaultTabs":
        osGalleryHelperSite::displayView();
        break;
        
    case "loadMoreButton":
	case "loadMoreScroll":
	case "loadMoreAuto":
        osGalleryHelperSite::displayViewAjax();
        break;
    
    case "searhResult":
        osGalleryHelperSite::showSearchResult();
        break;
    
    case "loadMoreButtonSearch":
	case "loadMoreScrollSearch":
	case "loadMoreAutoSearch":
        osGalleryHelperSite::showSearchResultAjax();
        break;

    default:
        echo 'error: '.$task;
        break;
}