<?php 
defined('_JEXEC') or die;

JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('bootstrap.loadCss', false, $this->direction);
JHtml::_('formbehavior.chosen', 'select');

$client = new JApplicationWebClient();

include_once 'functions.php';

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$template = $this->template;
$csspath = 'templates/'.$template.'/css/';
$jspath = 'templates/'.$template.'/js/';
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');
$menu = JMenu::getInstance('site');
$contentParams = $app->getParams('com_content');
$bodyClass = "body__".$contentParams->get('pageclass_sfx')." option-".$option." view-".$view." task-".$task." itemid-".$itemid;

if($client->mobile){
    $bodyClass .= ' mobile';
    if((isset($_COOKIE['disableMobile']) && $_COOKIE['disableMobile'] == 'false') || !isset($_COOKIE['disableMobile'])){
        $bodyClass .= " mobile_mode";
    }else{
        $bodyClass .= " desktop_mode";
    }
}

$viewport = "";
$nav = $app->getMenu();
  $nav = $nav->getActive();
  if ($nav == $menu->getDefault()) {
      $body_class = 'home';
  }else{
    $body_class = 'all';
  }
if($client->mobile && ((isset($_COOKIE['disableMobile']) && $_COOKIE['disableMobile']=='false') || !isset($_COOKIE['disableMobile']))) $viewport = "<meta id=\"viewport\" name=\"viewport\" content=\"width=device-width, initial-scale=1\">";

global $params;

$params = $this->params;

if ($params->get('logoFile')) $logo = $params->get('logoFile');

if ($params->get('footerLogoFile')) $footerLogo = $params->get('footerLogoFile');

if ($this->direction == 'rtl') $doc->addStyleSheet('media/jui/css/bootstrap-rtl.css');

$ie_warning = "";
if($client->browser == JApplicationWebClient::IE){
	$ie_warning ="
	    <!--[if lt IE 9]>\r\n
	      <div style=\"clear: both; text-align:center; position: relative;\">\r\n
	        <a href=\"http://windows.microsoft.com/en-us/internet-explorer/download-ie\">\r\n
	          <img src=\"templates/".$template."/images/warning_bar_0000_us.jpg\" border=\"0\" height=\"42\" width=\"820\" alt=\"You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today.\" />\r\n
	        </a>\r\n
	      </div>\r\n
	    <![endif]-->\r\n";
}

$hideByView = false;
switch($view){
	case 'article':
	case 'login':
	case 'search':
	case 'profile':
	case 'registration':
	case 'reset':
	case 'remind':
	case 'form':
	case 'productdetails':
	case 'cart':
	case 'user':
		$hideByView = true;
		break;
}

$hideByOption = false;
if($option == 'com_users' && $option == 'com_search') $hideByOption = true;

if(($option == 'com_content' && $layout == 'edit') || ($view == 'orders' && $option == 'com_virtuemart')) $hideByView = true;

$asideLeftWidth = "";
if($this->countModules('aside-left') && !$hideByOption && !$hideByView) $asideLeftWidth = $params->get('asideLeftWidth');

$asideRightWidth = "";
if($this->countModules('aside-right') && !$hideByOption && $view !== 'form') $asideRightWidth = $params->get('asideRightWidth');

$mainContentWidth = 12 - $asideLeftWidth - $asideRightWidth;

$themeLayout = 1;

global $containerClass, $rowClass;

$containerClass = 'container';
$rowClass = 'row';
if($themeLayout=='1'){
	$containerClass .= '-fluid';
    $rowClass .= '-fluid';
}

function display_position($pos){
    $html = "<!-- ".$pos." -->\n";
    $html .= "<div id=\"".$pos."\">\n";
    global $params;
    $layout = $params->get($pos.'_layout');
    if($layout == "normal"){
        global $containerClass, $rowClass;
        $html .= "<div class=\"row-container\">\n";
        $html .= "<div class=\"". $containerClass."\">\n";
        $html .= "<div class=\"".$rowClass."\">\n";
        $html .= "<jdoc:include type=\"modules\" name=\"".$pos."\" style=\"themeHtml5\" />\n";
        $html .= "</div>\n";
        $html .= "</div>\n";
        $html .= "</div>\n";
    } else {
        global $containerClass, $rowClass;
        $html .= "<div class=\"row-container-full\">\n";
        $html .= "<div class=\"".$containerClass."-full\">\n";
        $html .= "<div class=\"".$rowClass."\">\n";
        $html .= "<jdoc:include type=\"modules\" name=\"".$pos."\" style=\"themeHtml5\" />\n";
        $html .= "</div>\n";
        $html .= "</div>\n";
        $html .= "</div>\n";
    }
    $html .= "</div>\n";
    return $html;
}

$privacyMenuLink = $menu->getItem($params->get('privacy_link_menu'));

$privacy_link_url = JRoute::_($privacyMenuLink->link.'&Itemid='.$privacyMenuLink->id);
	
$termsMenuLink = $menu->getItem($params->get('terms_link_menu'));

$terms_link_url = JRoute::_($termsMenuLink->link.'&Itemid='.$termsMenuLink->id);

$todesktop = '';

if($params->get('todesktop') && $client->mobile && !$client->platform == JApplicationWebClient::IPAD){
	$todesktop = "<div class=\"span12\" id=\"to-desktop\">\r\n
  	<a href=\"#\">
  		<span class=\"";
  	if(isset($_COOKIE['disableMobile'])){
  		if($_COOKIE['disableMobile']=='false'){
  			$todesktop .= "to_desktop\">".$params->get('todesktop_text');
  		}
  		else{
  			$todesktop .= "to_mobile\">".$params->get('tomobile_text');
  		}
  	}
  	else{
  		$todesktop .= "to_desktop\">".$params->get('todesktop_text');
  	}
  	$todesktop .= "</span>
  	</a>
</div>";
} ?>