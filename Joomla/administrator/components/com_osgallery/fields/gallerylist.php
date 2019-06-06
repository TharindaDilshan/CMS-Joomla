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

class JFormFieldGalleryList extends JFormField{
  protected function getInput(){
    $db = JFactory::getDBO();
    $input = JFactory::getApplication()->input;
    $menuId = $input->get("id",0,"INT");
    $view = $input->getVar("view", "item");
    //var_dump($input);
    $app = JFactory::getApplication();
    if($menuId && $view == 'item') {
      $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".$menuId);
      $params = json_decode($db->loadResult());
    }elseif($menuId && $view == 'module'){
        // Module table
        $modTables = new  JTableModule(JFactory::getDbo());
        // Load module
        $modTables->load(array('id'=>$menuId));

        $params = new JRegistry();
        $params->loadString($modTables->params);
        
    }
    $query = "SELECT * FROM #__os_gallery";
    $db->setQuery($query);
    $galleries = $db->loadObjectList();

    $options=array();
    if(count($galleries)){
        foreach ($galleries as $gallery) {
            $options[] = JHtmlSelect::option($gallery->id, $gallery->title);
        }
    }
    if($view == 'item'){
        $selected = isset($params->gallery_list)?$params->gallery_list:'';
    }else{
        $selected = ($params->get('gallery_list', '') != '')?$params->get('gallery_list'):'';
    }
    $html = JHtmlSelect::genericlist($options, $this->name,
                'size="1" multiple="true" class="inputbox" ', 'value', 'text', $selected);

    return $html;
  }
}