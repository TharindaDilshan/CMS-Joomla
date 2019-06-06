<?php
/**
 * IceMegaMenu Extension for Joomla 3.0 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icemegamenu.html
 * @Support 	http://www.icetheme.com/Forums/IceMegaMenu/
 *
 */

$app    = JFactory::getApplication(); 
$doc = JFactory::getDocument();
$document =& $doc;
$template = $app->getTemplate();

$document->addScript('templates/'.$template.'/html/mod_icemegamenu/js/menu.js');
$document->addScript('templates/'.$template.'/html/mod_icemegamenu/js/jquery.rd-navbar.js');
$document->addStylesheet('templates/'.$template.'/html/mod_icemegamenu/css/navbar.css');

$document->addScriptDeclaration('jQuery(document).ready(function($){
	 RDMobilemenu_autoinit("#icemegamenu");
})');

$icemegamenu->render($params, 'modIceMegaMenuXMLCallback');
?>