<?php
/**
* @package OS Gallery
* @copyright 2019 OrdaSoft
* @author 2019 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com), Vladislav Prikhodko (vlados.vp1@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();
$input = $app->input;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/os-gallery.css");

$text_search_value = $input->getVar('textsearch', '');
$search_title = $params->get('search_title', 0);
$search_description = $params->get('search_description', 0);
$gallery_list = $params->get('gallery_list');
$itemId = $params->get('item_id', '');


?>
<div class="search_module_wrapper">
    <form action="index.php" method="GET" class="search_module_form">
        <input type="hidden" name="option" value="com_osgallery">
        <input type="hidden" name="task" value="searhResult">
        <input type="search" size="25" name="textsearch" class="searh_module_text" value="<?php echo $text_search_value; ?>">
        <input type="submit" class="btn button" value="Search">
        <input type="hidden" name="moduleId" value="<?php echo $module->id; ?>">
        <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>">
    </form>
</div>