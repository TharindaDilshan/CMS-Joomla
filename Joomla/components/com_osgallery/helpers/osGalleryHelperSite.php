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

class osGalleryHelperSite{

    public static function addMetaTags(){

        $document = JFactory::getDocument();
        $db = JFactory::getDbo();
        $path = JURI::getInstance()->toString();
        $isSEF = JFactory::getConfig()->get('sef');
        $concat = ($isSEF) ? '?' : '&';
        $pathFragments = explode($concat, $path);
        $endFrag = end($pathFragments);
        $params = new JRegistry;

        if(stripos($endFrag, 'os_image_id') !== false){
            $image_id = preg_replace("/[^0-9]/", '', $endFrag);
        }else{
            $image_id = false;
        }

        $query = "SELECT  gim.* , gal.id as galId FROM `#__os_gallery_img` as gim ".
                        "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                        "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                        "\n WHERE gim.id = ".(int) $image_id .
                        "\n ORDER BY cat.ordering ASC" ;
                

        $db->setQuery($query);
        $imageArr = $db->loadAssoc();

        if($imageArr['galId']){
            $galId = $imageArr['galId'];
            $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
            $db->setQuery($query);
            $paramsString = $db->loadResult();
            $params->loadString($paramsString);
        }



        if($image_id && is_array($imageArr)){                                       

            if($imageArr['params'] != "{}" && !empty($imageArr['params'])){
                $imageArr['params'] = json_decode(urldecode($imageArr['params']));
                $title = htmlspecialchars(strip_tags(substr($imageArr['params']->imgTitle, 0, 200)));
                $description = htmlspecialchars(strip_tags(substr($imageArr['params']->imgShortDescription, 0, 200)));
            }else{
                $title = htmlspecialchars(strip_tags(substr($document->getTitle(), 0, 200)));
                $description = htmlspecialchars(strip_tags(substr($document->getMetaData("description"), 0, 200)));
            }

            $config = JFactory::getConfig();
            $url = htmlspecialchars(JURI::getInstance()->toString());    
            $language = str_replace('-', '_', JFactory::getLanguage()->getTag());
            $siteName = htmlspecialchars($config->get('sitename'));
            $image_url = JURI::root(). "/images/com_osgallery/gal-" . $imageArr['galId'] . "/original/" . $imageArr['file_name'];


            // Type
          $document->addCustomTag("<meta property='og:type' content=\"article\" />");
          $document->addCustomTag("<meta property='locale' content=\"$language\" />");
          //Url
          if ($url != '')
            $document->addCustomTag("<meta property='og:url' content=\"$url\" />");
          //title
          if ($title != '')
            $document->addCustomTag("<meta property='og:title' content=\"$title\" />");
          //description
          if ($description != ''){
            $document->addCustomTag("<meta property='og:description' content=\"$description\" />");
          }else{
            $document->addCustomTag("<meta property='og:description' content=\"$title\" />");
          }
          // Image
          if (isset($image_url) && $image_url!='') {
            $document->addCustomTag("<meta property='og:image' content=\"$image_url\" />");
            $document->addCustomTag("<meta property='og:image:width' content=\"900\" />");
            $document->addCustomTag("<meta property='og:image:height' content=\"500\" />");
          }
          //Site Name

          if ($siteName != '')
              $document->addCustomTag("<meta property='og:site_name' content=\"$siteName\" />");
              //end meta

            if($params->get('twitter_enable')){

              //Card
              $document->addCustomTag("<meta property='twitter:card' content=\"summary_large_image\" />");
              $document->addCustomTag("<meta property='twitter:url' content=\"$url\" />");
              //site
              if ($siteName != '')
                $document->addCustomTag("<meta property='twitter:site' content=\"#$siteName\" />");
              //title
              if ($title != '')
                $document->addCustomTag("<meta property='twitter:title' content=\"$title\" />");
              //description
              if ($description != '')
                $document->addCustomTag("<meta property='twitter:description' content=\"$description\" />");
              //image
              if (isset($image_url) && $image_url!='')
                $document->addCustomTag("<meta property='twitter:image:src' content=\"$image_url\" />");
            }

          if(isset($image_url) && $image_url!=''){ ?>
            <link rel="image_src" href="<?php echo $image_url;?>" />
            <?php
          }

        }   
        return;
    }

    static function displayView($galIds = array()){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $input = $app->input;
        $menu = $app->getMenu()->getActive();
        
        $params = new JRegistry;
        $menuParams = new JRegistry;
        $task = $input->getCmd('task', '');
        $view = $input->getCmd('view', '');

        //include css
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/animate.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/os-gallery.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/hover.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/font-awesome.min.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.css");

        //include js
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/jQuer/jQuerGall-2.2.4.js");
        $document->addScriptDeclaration("jQuerGall=jQuerGall.noConflict();");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.js");

        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/imagesloadedGall.pkgd.min.js");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/isotope/isotope.pkgd.min.js");
        $document->addScript(JURI::root() . "components/com_osgallery/assets/js/osGallery.main.js");
        
        $imgEnd = '';
        
        if($menu){
            $menuParams = $menu->params;
            $itemId = $menu->id;
            if($galIds){
            }else{
                $galIds = $menuParams->get("gallery_list",array());
            }
        } else {
            $itemId = '';
        }
        //var_dump($menu);
        if (!$galIds && $input->get('galId')) {
            $galIds[] = $input->get('galId');
        }
        $buttons = array();
        foreach ($galIds as $galId) {
            
            if($galId){
                
                //load params
                $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
                $db->setQuery($query);
                $paramsString = $db->loadResult();
                if($paramsString){
                    $params->loadString($paramsString);
                }
                if($params->get('externalGallerySettings', 0) > 0){
                    $query = "SELECT params FROM #__os_gallery WHERE id=".$params->get('externalGallerySettings', 0);
                    $db->setQuery($query);
                    $paramsString = $db->loadResult();
                    if($paramsString){
                        $params->loadString($paramsString);
                    }
                }
            }

            // LoadMore button
            $numberImagesEffect = $params->get("number_images_effect", "zoomIn");
            $numberImagesAtOnceEffect = $params->get("number_images_at_once_effect", "zoomIn");   
            $showLoadMore = $params->get("showLoadMore", 0);
            $numberImages = $params->get("number_images", 5);
            $downloadButton = $params->get("showDownload", 0);
            
            // Order by params
            $selectOrder = "drag_and_drop";
            $orderBy = "ASC";
            $selectOrder = $params->get("order", "drag_and_drop");
            $orderBy = ($selectOrder == 'drag_and_drop') ? 'ASC' : $params->get("orderBy", "ASC");
            
            if($selectOrder == 'drag_and_drop'){
                $selectOrder = "gim.ordering";
            }else{
                $selectOrder = "gim.".$selectOrder;
            }
            $order = " ORDER BY cat.ordering ASC, " . $selectOrder . ' ' . $orderBy . ' ';


            if($input->get('end', '') == -1){
                return;
            }

            if ($input->get('end', '')){
                $end =$input->get('end');
            }else{
                $end = 0;
            }

            // ---------------
            if($galId){
                if (!$showLoadMore ) {
                    // getting Images
                    $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                            "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                            "\n WHERE cat.fk_gal_id =$galId AND gal.published=1 AND gim.publish=1 ". $order;
                            
                            //var_dump($query); exit;
                    $db->setQuery($query);
                    $result =$db->loadObjectList();
                    
                    // ordering categories
//                    usort($result, function($a, $b) {
//                        return $a->cat_ordering>$b->cat_ordering;
//                    });
//                    var_dump($result);
                } else {

                    if ($input->get("catId", '')) {
                        $cat_id_array =  array();
                        $cat_id_array[] = $input->get("catId");
                    } else {
                        $query = "SELECT DISTINCT fk_cat_id FROM #__os_gallery_connect";
                        $db->setQuery($query);
                        $cat_id_array = $db->loadColumn();
                    }

                    $result = array();
                    foreach ($cat_id_array as $cat_id) {
                        // getting Images
                        $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                                "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                                "\n WHERE cat.fk_gal_id =$galId AND gal.published=1 AND gim.publish=1 AND gc.fk_cat_id=$cat_id " . $order .
                                "\n LIMIT $end," . ($numberImages + 1);
                        $db->setQuery($query);
                        $imgArr = $db->loadObjectList();
                        
                        $query = "SELECT  COUNT(*) as count FROM #__os_gallery_img as gim ".
                                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                                "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                                "\n WHERE cat.fk_gal_id =$galId AND gal.published=1 AND gim.publish=1 AND gc.fk_cat_id=$cat_id " . $order;
                                
                        
                        $db->setQuery($query);
                        $img_count = $db->loadObjectList();
                        
                        $img_count_cat[$cat_id] = $img_count[0]->count;

                        if (count($imgArr) < ($numberImages+1)) {
                            $imgEnd = -1;
                        } else {
                             unset($imgArr[count($imgArr)-1]);
                        }
                        $result = array_merge($result, $imgArr);
                    }
                    // ordering categories
//                    usort($result, function($a, $b) {
//                        return $a->cat_ordering>$b->cat_ordering;
//                    });
                }

                if($result){

                    $images = array();
                    foreach ($result as $image) {
                        if(!isset($images[$image->fk_cat_id])){
                           $images[$image->fk_cat_id] = array();
                        }
                        $images[$image->fk_cat_id][] = $image;
                    }

                    //ordering images
//                    foreach ($images as $key => $imageArr) {
//                        usort($imageArr, "self::sortByOrdering");
//                        $images[$key] = $imageArr;
//                    }

                    //get cat params array
                    $query = "SELECT DISTINCT id,params FROM #__os_gallery_categories".
                            "\n WHERE fk_gal_id =$galId";
                    $db->setQuery($query);
                    $catParamsArray = $db->loadObjectList('id');

                    //get cat params array
                    $query = "SELECT DISTINCT galImg.id,galImg.params FROM #__os_gallery_img as galImg".
                            "\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id = galImg.id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id = galCon.fk_cat_id".
                            "\n WHERE cat.fk_gal_id =$galId";
                    $db->setQuery($query);
                    $imgParamsArray = $db->loadObjectList('id');
                }else{
                    $images = array();
                    $imgParamsArray = array("1"=>(object) array("params"=>'{}'));
                    $catParamsArray = array("1"=>(object) array("params"=>'{}'));
                }
            }else{
                $app->enqueueMessage("Select gallery for menu(".$itemId.").", 'error');
                return;
            }

            if($params->get("helper_thumbnail")){
                $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancybox-thumbs.css");
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancyboxGall-thumbs.js");
            }
            if($params->get("mouse_wheel",1)){
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.mousewheel-3.0.6.pack.js");
            }


            //imgWidthThumb
            $imgWidthThumb = $params->get("imgWidth", false);
            //imgHeightThumb
            $imgHeightThumb = $params->get("imgHeight", false);
            //Background Color (complete)
            $os_fancybox_background = $params->get("fancy_box_background", "rgba(0, 0, 0, 0.75)");
            //Close by click (complete)
            $click_close = ($params->get("click_close", 1) == '1') ? '"close"' : 'false';
            //Open/Close Effect (complete)
            $open_close_effect = ($params->get("open_close_effect", "fade") == 'none') ? false : $params->get("open_close_effect", "fade");
            //Open/Close speed, ms (complete)
            $open_close_speed = $params->get("open_close_speed", 500);
            //Prev/Next Effect (complete)
            $prev_next_effect = ($params->get("prev_next_effect", "fade") == 'none') ? false : $params->get("prev_next_effect", "fade");
            // Prev/Next speed, ms (complete)
            $prev_next_speed = $params->get("prev_next_speed", 500);
            // Show Image Title
            $showImgTitle = $params->get("showImgTitle", 0);   
            // Show Image Description
            $showImgDescription = $params->get("showImgDescription", 0);   
            // Loop (complete)
            $loop = ($params->get("loop", 1) == '1')? true : $params->get("loop", 1);
            //Thumbnail Autostart
            $thumbnail_autostart = ($params->get("helper_thumbnail") == '1') ? 'true' : 'false';
            // Prev/Next Arrows (complete)
            $os_fancybox_arrows = ($params->get("os_fancybox_arrows", 1) == '1') ? true : $params->get("os_fancybox_arrows", 1);
            // Next Click (complete)
            $next_click = ($params->get("next_click", 0) == '1') ? 'next' : 'zoom';
            // Mouse Wheel (complete)
            $mouse_wheel = ($params->get("mouse_wheel", 1) == '1') ? true : 'false';
            // Autoplay (complete)
            $os_fancybox_autoplay = ($params->get("os_fancybox_autoplay", 0) == '1') ? true : 'false' ;
            // Autoplay Speed, ms(complete)
            $autoplay_speed = $params->get("autoplay_speed", 3000);
            // Thumbnails position
            $os_fancybox_thumbnail_position = $params->get("os_fancybox_thumbnail_position", "thumb_right");
            $os_fancybox_thumbnail_axis = ($params->get("os_fancybox_thumbnail_position", "thumb_right") == "thumb_bottom") ? 'x' : 'y' ;

            //Buttons panel block
            $start_slideshow_button = ($params->get("start_slideshow_button") == '1') ? 'slideShow'  : "";
            $full_screen_button = ($params->get("full_screen_button") == '1') ? 'fullScreen'  : "";
            $thumbnails_button = ($params->get("thumbnails_button") == '1') ? 'thumbs'  : "";
            $share_button = ($params->get("share_button") == '1') ? 'share'  : "";
            $download_button = ($params->get("download_button") == '1') ? 'download'  : "";
            $zoom_button = ($params->get("zoom_button") == '1') ? 'zoom'  : "";
            $left_arrow = ($params->get("left_arrow") == '1') ? 'arrowLeft'  : "";
            $right_arrow = ($params->get("right_arrow") == '1') ? 'arrowRight'  : "";
            $close_button = ($params->get("close_button") == '1') ? 'close'  : "";
            //Buttons panel block


            $infobar = ($params->get("infobar", 1) == '1') ? true : 'false';

            //Social sharing
            if( $share_button == "share" && 
                !( $params->get('facebook_enable') || $params->get('googleplus_enable') || $params->get('vkontacte_enable') 
                  || $params->get('odnoklassniki_enable') || $params->get('twitter_enable') || $params->get('pinterest_enable') 
                  || $params->get('linkedin_enable') )  
              ) $share_button = "" ;
            $share_tpl = '';
            //$share_tpl = '<div class="os_fancybox-share"><div class="container"><h1>{{SHARE}}</h1><p class="os_fancybox-share__links">';
            //var_dump($params->get('facebook_enable'));
//            if($params->get('facebook_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_facebook" rel="noindex, nofollow"  href="https://www.facebook.com/sharer.php?u={{url}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-facebook-2" title="Facebook" alt="Facebook" class="at-icon at-icon-facebook"><title id="at-svg-facebook-2">Facebook</title><g><path d="M22 5.16c-.406-.054-1.806-.16-3.43-.16-3.4 0-5.733 1.825-5.733 5.17v2.882H9v3.913h3.837V27h4.604V16.965h3.823l.587-3.913h-4.41v-2.5c0-1.123.347-1.903 2.198-1.903H22V5.16z" fill-rule="evenodd"></path></g></svg></span><span>Facebook</span></a>';
//                    
//            if($params->get('googleplus_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_googleplus" rel="noindex, nofollow"  href="https://plus.google.com/share?url={{url}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-google_plusone_share-8" title="Google+" alt="Google+" class="at-icon at-icon-google_plusone_share"><title id="at-svg-google_plusone_share-8">Google+</title><g><path d="M12 15v2.4h3.97c-.16 1.03-1.2 3.02-3.97 3.02-2.39 0-4.34-1.98-4.34-4.42s1.95-4.42 4.34-4.42c1.36 0 2.27.58 2.79 1.08l1.9-1.83C15.47 9.69 13.89 9 12 9c-3.87 0-7 3.13-7 7s3.13 7 7 7c4.04 0 6.72-2.84 6.72-6.84 0-.46-.05-.81-.11-1.16H12zm15 0h-2v-2h-2v2h-2v2h2v2h2v-2h2v-2z" fill-rule="evenodd"></path></g></svg></span><span>Google +</span></a>';
//
//            if($params->get('vkontacte_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_vkontacte" rel="noindex, nofollow"  href="http://vk.com/share.php?url={{url}}&amp;title={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-vk-170" title="Vkontakte" alt="Vkontakte" class="at-icon at-icon-vk"><title id="at-svg-vk-170">Vkontakte</title><g><path d="M26.712 10.96s-.167-.48-1.21-.348l-3.447.024a.785.785 0 0 0-.455.072s-.204.108-.3.37a22.1 22.1 0 0 1-1.28 2.695c-1.533 2.61-2.156 2.754-2.407 2.587-.587-.372-.43-1.51-.43-2.323 0-2.54.382-3.592-.756-3.868-.37-.084-.646-.144-1.616-.156-1.232-.012-2.274 0-2.86.287-.396.193-.695.624-.515.648.227.036.742.143 1.017.515 0 0 .3.49.347 1.568.13 2.982-.48 3.353-.48 3.353-.466.252-1.28-.167-2.478-2.634 0 0-.694-1.222-1.233-2.563-.097-.25-.288-.383-.288-.383s-.216-.168-.527-.216l-3.28.024c-.504 0-.683.228-.683.228s-.18.19-.012.587c2.562 6.022 5.483 9.04 5.483 9.04s2.67 2.79 5.7 2.597h1.376c.418-.035.634-.263.634-.263s.192-.214.18-.61c-.024-1.843.838-2.12.838-2.12.838-.262 1.915 1.785 3.065 2.575 0 0 .874.6 1.532.467l3.064-.048c1.617-.01.85-1.352.85-1.352-.06-.108-.442-.934-2.286-2.647-1.916-1.784-1.665-1.496.658-4.585 1.413-1.88 1.976-3.03 1.796-3.52z" fill-rule="evenodd"></path></g></svg></span><span>Vkontakte</span></a>';
//
//            if($params->get('odnoklassniki_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_odnoklassniki" rel="noindex, nofollow"  href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={{url}}&amp;st.comments={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-odnoklassniki_ru-116" title="Odnoklassniki" alt="Odnoklassniki" class="at-icon at-icon-odnoklassniki_ru"><title id="at-svg-odnoklassniki_ru-116">Odnoklassniki</title><g><path d="M16.5 16.15A6.15 6.15 0 0 0 22.65 10c0-3.39-2.75-6.14-6.15-6.14-3.4 0-6.15 2.75-6.15 6.14.01 3.4 2.76 6.15 6.15 6.15zm0-9.17c1.67 0 3.02 1.35 3.02 3.02s-1.35 3.02-3.02 3.02-3.02-1.35-3.02-3.02 1.35-3.02 3.02-3.02zm7.08 9.92c-.35-.7-1.31-1.28-2.58-.27-1.73 1.36-4.5 1.36-4.5 1.36s-2.77 0-4.5-1.36c-1.28-1.01-2.24-.43-2.59.27-.6 1.22.08 1.8 1.62 2.79 1.32.85 3.13 1.16 4.3 1.28l-.98.98c-1.38 1.37-2.7 2.7-3.62 3.62-.55.55-.55 1.438 0 1.99l.17.17c.55.55 1.44.55 1.99 0l3.62-3.622 3.62 3.62c.55.55 1.44.55 1.99 0l.17-.17c.55-.55.55-1.44 0-1.99l-3.62-3.62-.98-.98c1.17-.12 2.96-.438 4.27-1.28 1.55-.988 2.23-1.58 1.62-2.788z"></path></g></svg></span><span>Odnoklassniki</span></a>';
//
//            if($params->get('twitter_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_twitter" rel="noindex, nofollow"  href="https://twitter.com/share?url={{url}}&amp;text={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-twitter-3" title="Twitter" alt="Twitter" class="at-icon at-icon-twitter"><title id="at-svg-twitter-3">Twitter</title><g><path d="M27.996 10.116c-.81.36-1.68.602-2.592.71a4.526 4.526 0 0 0 1.984-2.496 9.037 9.037 0 0 1-2.866 1.095 4.513 4.513 0 0 0-7.69 4.116 12.81 12.81 0 0 1-9.3-4.715 4.49 4.49 0 0 0-.612 2.27 4.51 4.51 0 0 0 2.008 3.755 4.495 4.495 0 0 1-2.044-.564v.057a4.515 4.515 0 0 0 3.62 4.425 4.52 4.52 0 0 1-2.04.077 4.517 4.517 0 0 0 4.217 3.134 9.055 9.055 0 0 1-5.604 1.93A9.18 9.18 0 0 1 6 23.85a12.773 12.773 0 0 0 6.918 2.027c8.3 0 12.84-6.876 12.84-12.84 0-.195-.005-.39-.014-.583a9.172 9.172 0 0 0 2.252-2.336" fill-rule="evenodd"></path></g></svg></span><span>Twitter</span></a>';
//
//            if($params->get('pinterest_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_pinterest" rel="noindex, nofollow"  href="http://pinterest.com/pin/create/button/?url={{url}}&amp;description={{descr}}&amp;media={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-pinterest_share-6" title="Pinterest" alt="Pinterest" class="at-icon at-icon-pinterest_share"><title id="at-svg-pinterest_share-6">Pinterest</title><g><path d="M7 13.252c0 1.81.772 4.45 2.895 5.045.074.014.178.04.252.04.49 0 .772-1.27.772-1.63 0-.428-1.174-1.34-1.174-3.123 0-3.705 3.028-6.33 6.947-6.33 3.37 0 5.863 1.782 5.863 5.058 0 2.446-1.054 7.035-4.468 7.035-1.232 0-2.286-.83-2.286-2.018 0-1.742 1.307-3.43 1.307-5.225 0-1.092-.67-1.977-1.916-1.977-1.692 0-2.732 1.77-2.732 3.165 0 .774.104 1.63.476 2.336-.683 2.736-2.08 6.814-2.08 9.633 0 .87.135 1.728.224 2.6l.134.137.207-.07c2.494-3.178 2.405-3.8 3.533-7.96.61 1.077 2.182 1.658 3.43 1.658 5.254 0 7.614-4.77 7.614-9.067C26 7.987 21.755 5 17.094 5 12.017 5 7 8.15 7 13.252z" fill-rule="evenodd"></path></g></svg></span><span>Pinterest</span></a>';
//
//            if($params->get('linkedin_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_linkedin" rel="noindex, nofollow"  href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{url}}&amp;title={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-linkedin-9" title="LinkedIn" alt="LinkedIn" class="at-icon at-icon-linkedin"><title id="at-svg-linkedin-9">LinkedIn</title><g><path d="M26 25.963h-4.185v-6.55c0-1.56-.027-3.57-2.175-3.57-2.18 0-2.51 1.7-2.51 3.46v6.66h-4.182V12.495h4.012v1.84h.058c.558-1.058 1.924-2.174 3.96-2.174 4.24 0 5.022 2.79 5.022 6.417v7.386zM8.23 10.655a2.426 2.426 0 0 1 0-4.855 2.427 2.427 0 0 1 0 4.855zm-2.098 1.84h4.19v13.468h-4.19V12.495z" fill-rule="evenodd"></path></g></svg></span><span>Linkedin</span></a>';

            //$share_tpl .= '<p><input class="os_fancybox-share__input" type="text" value="{{url_raw}}" /></p></p></div>';
             //$share_tpl .= '</p></div></div>';
            //Social sharing


            $backText = $params->get("backButtonText",'back');
            $numColumns = $params->get("num_column",4);
            $minImgEnable = $params->get("minImgEnable",1);
            $minImgSize = $params->get("minImgSize",225);
            $imageMargin = $params->get("image_margin")/2;
            $imageMargin=(float)str_replace(',','.',$imageMargin); 
            
            $loadMoreButtonText = $params->get("loadMoreButtonText", "Load More");
            $load_more_background = $params->get('load_more_background', '#12BBC5') ;                      
            $imageHover = $params->get("imageHover", "none");
            
            $imgTextPosition = $params->get("imgTextPosition", "onImage");
            $imgTextHeight = "";
            $imgTextHeight = $params->get("imgTextHeight", 40);
            $imgTextStyle = "";
            $imgTextStyle = "style='min-height:".$imgTextHeight."px; max-height:".$imgTextHeight."px; height:".$imgTextHeight."px; overflow: hidden;'";
            
            $imgMaxlengthTitle = $params->get("imgMaxlengthTitle", 100);
            $imgMaxlengthDesc = $params->get("imgMaxlengthDesc", 100);
            
            $galleryLayout = $params->get("galleryLayout", "defaultTabs");
            $masonryLayout = $params->get("masonryLayout", "default");


            require self::findView($galleryLayout);

        }
    }


     static function displayViewAjax(){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $input = $app->input;
        $menu = $app->getMenu()->getActive();
        $params = new JRegistry;
        $view = $input->getCmd('view', '');

        //include css
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/os-gallery.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/hover.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/font-awesome.min.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.css");

        //include js
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/jQuer/jQuerGall-2.2.4.js");
        $document->addScriptDeclaration("jQuerGall=jQuerGall.noConflict();");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.js");

        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/imagesloadedGall.pkgd.min.js");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/isotope/isotope.pkgd.min.js");
        $document->addScript(JURI::root() . "components/com_osgallery/assets/js/osGallery.main.js");
        

        $itemId = $menu->id;
        $imgEnd = '';

        $galId = $input->get('galId');

        $buttons = array();

            if($galId){
                //load params
                $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
                $db->setQuery($query);
                $paramsString = $db->loadResult();
                if($paramsString){
                    $params->loadString($paramsString);
                }
                if($params->get('externalGallerySettings', 0) > 0){
                    $query = "SELECT params FROM #__os_gallery WHERE id=".$params->get('externalGallerySettings', 0);
                    $db->setQuery($query);
                    $paramsString = $db->loadResult();
                    if($paramsString){
                        $params->loadString($paramsString);
                    }
                }
            }

            // LoadMore button
            $numberImagesEffect = $params->get("number_images_effect", "zoomIn");
            $numberImagesAtOnceEffect = $params->get("number_images_at_once_effect", "zoomIn");
            $numberImages = $params->get("number_images_at_once", 5);
            $showLoadMore = $params->get("showLoadMore", 0);
            $downloadButton = $params->get("showDownload", 0);
            
            // Order by params
            $selectOrder = "drag_and_drop";
            $orderBy = "ASC";
            $selectOrder = $params->get("order", "drag_and_drop");
            $orderBy = ($selectOrder == 'drag_and_drop') ? 'ASC' : $params->get("orderBy", "ASC");
            
            if($selectOrder == 'drag_and_drop'){
                $selectOrder = "gim.ordering";
            }else{
                $selectOrder = "gim.".$selectOrder;
            }
            $order = " ORDER BY " . $selectOrder . ' ' . $orderBy . ' ';

            if($input->get('end', '') == -1){
                return;
            }

            if ($input->get('end', '')){
                $end =$input->get('end');
            }
            else{
                $end = 0;
            }

            if($galId){
                if (!$showLoadMore ) {
                    // getting Images
                    $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                            "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                            "\n WHERE cat.fk_gal_id =$galId AND gal.published=1 AND gim.publish=1". $order;
                            
                    $db->setQuery($query);
                    $result =$db->loadObjectList();
                    
                    // ordering categories
//                    usort($result, function($a, $b) {
//                        return $a->cat_ordering>$b->cat_ordering;
//                    });
                } else {

                    if ($input->get("catId", '')) {
                        $cat_id_array =  array();
                        $cat_id_array[] = $input->get("catId");
                    } else {
                        $query = "SELECT DISTINCT fk_cat_id FROM #__os_gallery_connect";
                        $db->setQuery($query);
                        $cat_id_array = $db->loadColumn();
                        
                    }
                    
                    $result = array();
                    if ($input->get("oneimg")){
                        $os_oneImgId = $input->get("oneimg");
                        foreach ($cat_id_array as $cat_id) {
                        // getting Images
                        $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                                "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                                "\n WHERE cat.fk_gal_id =$galId AND gal.published=1 AND gim.publish=1 AND gim.id =$os_oneImgId AND gc.fk_cat_id=$cat_id" ;
                               
                        $db->setQuery($query);
                        $imgArr = $db->loadObjectList();
                        
                        
                        $result = array_merge($result, $imgArr);
                    }
                    } else {
                        
                    
                    foreach ($cat_id_array as $cat_id) {
                        // getting Images
                        $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                                "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                                "\n WHERE cat.fk_gal_id =$galId AND gal.published=1 AND gim.publish=1 AND gc.fk_cat_id=$cat_id" . $order .
                                //"\n , cat.ordering ASC" . 
                                "\n LIMIT $end," . ($numberImages + 1);
                        $db->setQuery($query);
                        $imgArr = $db->loadObjectList();
                        
                        if (count($imgArr) < ($numberImages+1)) {
                            $imgEnd = -1;
                        } else {
                             unset($imgArr[count($imgArr)-1]);
                        }
                        $result = array_merge($result, $imgArr);
                    }
                    }
                    // ordering categories
//                    usort($result, function($a, $b) {
//                        return $a->cat_ordering>$b->cat_ordering;
//                    });
                }

                if($result){

                    $images = array();
                    foreach ($result as $image) {
                        if(!isset($images[$image->fk_cat_id])){
                           $images[$image->fk_cat_id] = array();
                        }
                        $images[$image->fk_cat_id][] = $image;
                    }

                    //ordering images
//                    foreach ($images as $key => $imageArr) {
//                        usort($imageArr, "self::sortByOrdering");
//                        $images[$key] = $imageArr;
//                    }

                    //get cat params array
                    $query = "SELECT DISTINCT id,params FROM #__os_gallery_categories".
                            "\n WHERE fk_gal_id =$galId";
                    $db->setQuery($query);
                    $catParamsArray = $db->loadObjectList('id');

                    //get cat params array
                    $query = "SELECT DISTINCT galImg.id,galImg.params FROM #__os_gallery_img as galImg".
                            "\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id = galImg.id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id = galCon.fk_cat_id".
                            "\n WHERE cat.fk_gal_id =$galId";
                    $db->setQuery($query);
                    $imgParamsArray = $db->loadObjectList('id');
                }else{
                    $images = array();
                    $imgParamsArray = array("1"=>(object) array("params"=>'{}'));
                    $catParamsArray = array("1"=>(object) array("params"=>'{}'));
                }
            }else{
                $app->enqueueMessage("Select gallery for menu(".$itemId.").", 'error');
                return;
            }

            if($params->get("helper_thumbnail")){
                $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancybox-thumbs.css");
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancyboxGall-thumbs.js");
            }
            if($params->get("mouse_wheel",1)){
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.mousewheel-3.0.6.pack.js");
            }


             //imgWidthThumb
            $imgWidthThumb = $params->get("imgWidth", false);
            //imgHeightThumb
            $imgHeightThumb = $params->get("imgHeight", false);
            //Background Color (complete)
            $os_fancybox_background = $params->get("fancy_box_background", "rgba(0, 0, 0, 0.75)");
            //Close by click (complete)
            $click_close = ($params->get("click_close", 1) == '1') ? '"close"' : 'false';
            //Open/Close Effect (complete)
            $open_close_effect = ($params->get("open_close_effect", "fade") == 'none') ? false : $params->get("open_close_effect", "fade");
            //Open/Close speed, ms (complete)
            $open_close_speed = $params->get("open_close_speed", 500);
            //Prev/Next Effect (complete)
            $prev_next_effect = ($params->get("prev_next_effect", "fade") == 'none') ? false : $params->get("prev_next_effect", "fade");
            // Prev/Next speed, ms (complete)
            $prev_next_speed = $params->get("prev_next_speed", 500);
            // Show Image Title
            $showImgTitle = $params->get("showImgTitle", 0);   
            // Show Image Description
            $showImgDescription = $params->get("showImgDescription", 0);   
            // Loop (complete)
            $loop = ($params->get("loop", 1) == '1')? true : $params->get("loop", 1);
            //Thumbnail Autostart
            $thumbnail_autostart = ($params->get("helper_thumbnail") == '1') ? 'true' : 'false';
            // Prev/Next Arrows (complete)
            $os_fancybox_arrows = ($params->get("os_fancybox_arrows", 1) == '1') ? true : $params->get("os_fancybox_arrows", 1);
            // Next Click (complete)
            $next_click = ($params->get("next_click", 0) == '1') ? 'next' : 'zoom';
            // Mouse Wheel (complete)
            $mouse_wheel = ($params->get("mouse_wheel", 1) == '1') ? true : 'false';
            // Autoplay (complete)
            $os_fancybox_autoplay = ($params->get("os_fancybox_autoplay", 0) == '1') ? true : 'false' ;
            // Autoplay Speed, ms(complete)
            $autoplay_speed = $params->get("autoplay_speed", 3000);
            // Thumbnails position
            $os_fancybox_thumbnail_position = $params->get("os_fancybox_thumbnail_position", "thumb_right");
            $os_fancybox_thumbnail_axis = ($params->get("os_fancybox_thumbnail_position", "thumb_right") == "thumb_bottom") ? 'x' : 'y' ;

            //Buttons panel block
            $start_slideshow_button = ($params->get("start_slideshow_button") == '1') ? 'slideShow'  : "";
            $full_screen_button = ($params->get("full_screen_button") == '1') ? 'fullScreen'  : "";
            $thumbnails_button = ($params->get("thumbnails_button") == '1') ? 'thumbs'  : "";
            $share_button = ($params->get("share_button") == '1') ? 'share'  : "";
            $download_button = ($params->get("download_button") == '1') ? 'download'  : "";
            $zoom_button = ($params->get("zoom_button") == '1') ? 'zoom'  : "";
            $left_arrow = ($params->get("left_arrow") == '1') ? 'arrowLeft'  : "";
            $right_arrow = ($params->get("right_arrow") == '1') ? 'arrowRight'  : "";
            $close_button = ($params->get("close_button") == '1') ? 'close'  : "";
            //Buttons panel block

            $infobar = ($params->get("infobar", 1) == '1') ? true : 'false';

            //Social sharing
            if( $share_button == "share" && 
                !( $params->get('facebook_enable') || $params->get('googleplus_enable') || $params->get('vkontacte_enable') 
                  || $params->get('odnoklassniki_enable') || $params->get('twitter_enable') || $params->get('pinterest_enable') 
                  || $params->get('linkedin_enable') )  
              ) $share_button = "" ;

            $share_tpl = '<div class="os_fancybox-share"><div class="container"><h1>{{SHARE}}</h1><p class="os_fancybox-share__links">';

            if($params->get('facebook_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_facebook" rel="noindex, nofollow"  href="https://www.facebook.com/sharer.php?u={{url}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-facebook-2" title="Facebook" alt="Facebook" class="at-icon at-icon-facebook"><title id="at-svg-facebook-2">Facebook</title><g><path d="M22 5.16c-.406-.054-1.806-.16-3.43-.16-3.4 0-5.733 1.825-5.733 5.17v2.882H9v3.913h3.837V27h4.604V16.965h3.823l.587-3.913h-4.41v-2.5c0-1.123.347-1.903 2.198-1.903H22V5.16z" fill-rule="evenodd"></path></g></svg><span>Facebook</span></a>';
                    
            if($params->get('googleplus_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_googleplus" rel="noindex, nofollow"  href="https://plus.google.com/share?url={{url}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-google_plusone_share-8" title="Google+" alt="Google+" class="at-icon at-icon-google_plusone_share"><title id="at-svg-google_plusone_share-8">Google+</title><g><path d="M12 15v2.4h3.97c-.16 1.03-1.2 3.02-3.97 3.02-2.39 0-4.34-1.98-4.34-4.42s1.95-4.42 4.34-4.42c1.36 0 2.27.58 2.79 1.08l1.9-1.83C15.47 9.69 13.89 9 12 9c-3.87 0-7 3.13-7 7s3.13 7 7 7c4.04 0 6.72-2.84 6.72-6.84 0-.46-.05-.81-.11-1.16H12zm15 0h-2v-2h-2v2h-2v2h2v2h2v-2h2v-2z" fill-rule="evenodd"></path></g></svg><span>Google +</span></a>';

            if($params->get('vkontacte_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_vkontacte" rel="noindex, nofollow"  href="http://vk.com/share.php?url={{url}}&amp;title={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-vk-170" title="Vkontakte" alt="Vkontakte" class="at-icon at-icon-vk"><title id="at-svg-vk-170">Vkontakte</title><g><path d="M26.712 10.96s-.167-.48-1.21-.348l-3.447.024a.785.785 0 0 0-.455.072s-.204.108-.3.37a22.1 22.1 0 0 1-1.28 2.695c-1.533 2.61-2.156 2.754-2.407 2.587-.587-.372-.43-1.51-.43-2.323 0-2.54.382-3.592-.756-3.868-.37-.084-.646-.144-1.616-.156-1.232-.012-2.274 0-2.86.287-.396.193-.695.624-.515.648.227.036.742.143 1.017.515 0 0 .3.49.347 1.568.13 2.982-.48 3.353-.48 3.353-.466.252-1.28-.167-2.478-2.634 0 0-.694-1.222-1.233-2.563-.097-.25-.288-.383-.288-.383s-.216-.168-.527-.216l-3.28.024c-.504 0-.683.228-.683.228s-.18.19-.012.587c2.562 6.022 5.483 9.04 5.483 9.04s2.67 2.79 5.7 2.597h1.376c.418-.035.634-.263.634-.263s.192-.214.18-.61c-.024-1.843.838-2.12.838-2.12.838-.262 1.915 1.785 3.065 2.575 0 0 .874.6 1.532.467l3.064-.048c1.617-.01.85-1.352.85-1.352-.06-.108-.442-.934-2.286-2.647-1.916-1.784-1.665-1.496.658-4.585 1.413-1.88 1.976-3.03 1.796-3.52z" fill-rule="evenodd"></path></g></svg><span>Vkontakte</span></a>';

            if($params->get('odnoklassniki_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_odnoklassniki" rel="noindex, nofollow"  href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={{url}}&amp;st.comments={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-odnoklassniki_ru-116" title="Odnoklassniki" alt="Odnoklassniki" class="at-icon at-icon-odnoklassniki_ru"><title id="at-svg-odnoklassniki_ru-116">Odnoklassniki</title><g><path d="M16.5 16.15A6.15 6.15 0 0 0 22.65 10c0-3.39-2.75-6.14-6.15-6.14-3.4 0-6.15 2.75-6.15 6.14.01 3.4 2.76 6.15 6.15 6.15zm0-9.17c1.67 0 3.02 1.35 3.02 3.02s-1.35 3.02-3.02 3.02-3.02-1.35-3.02-3.02 1.35-3.02 3.02-3.02zm7.08 9.92c-.35-.7-1.31-1.28-2.58-.27-1.73 1.36-4.5 1.36-4.5 1.36s-2.77 0-4.5-1.36c-1.28-1.01-2.24-.43-2.59.27-.6 1.22.08 1.8 1.62 2.79 1.32.85 3.13 1.16 4.3 1.28l-.98.98c-1.38 1.37-2.7 2.7-3.62 3.62-.55.55-.55 1.438 0 1.99l.17.17c.55.55 1.44.55 1.99 0l3.62-3.622 3.62 3.62c.55.55 1.44.55 1.99 0l.17-.17c.55-.55.55-1.44 0-1.99l-3.62-3.62-.98-.98c1.17-.12 2.96-.438 4.27-1.28 1.55-.988 2.23-1.58 1.62-2.788z"></path></g></svg><span>Odnoklassniki</span></a>';

            if($params->get('twitter_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_twitter" rel="noindex, nofollow"  href="https://twitter.com/share?url={{url}}&amp;text={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-twitter-3" title="Twitter" alt="Twitter" class="at-icon at-icon-twitter"><title id="at-svg-twitter-3">Twitter</title><g><path d="M27.996 10.116c-.81.36-1.68.602-2.592.71a4.526 4.526 0 0 0 1.984-2.496 9.037 9.037 0 0 1-2.866 1.095 4.513 4.513 0 0 0-7.69 4.116 12.81 12.81 0 0 1-9.3-4.715 4.49 4.49 0 0 0-.612 2.27 4.51 4.51 0 0 0 2.008 3.755 4.495 4.495 0 0 1-2.044-.564v.057a4.515 4.515 0 0 0 3.62 4.425 4.52 4.52 0 0 1-2.04.077 4.517 4.517 0 0 0 4.217 3.134 9.055 9.055 0 0 1-5.604 1.93A9.18 9.18 0 0 1 6 23.85a12.773 12.773 0 0 0 6.918 2.027c8.3 0 12.84-6.876 12.84-12.84 0-.195-.005-.39-.014-.583a9.172 9.172 0 0 0 2.252-2.336" fill-rule="evenodd"></path></g></svg><span>Twitter</span></a>';

            if($params->get('pinterest_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_pinterest" rel="noindex, nofollow"  href="http://pinterest.com/pin/create/button/?url={{url}}&amp;description={{descr}}&amp;media={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-pinterest_share-6" title="Pinterest" alt="Pinterest" class="at-icon at-icon-pinterest_share"><title id="at-svg-pinterest_share-6">Pinterest</title><g><path d="M7 13.252c0 1.81.772 4.45 2.895 5.045.074.014.178.04.252.04.49 0 .772-1.27.772-1.63 0-.428-1.174-1.34-1.174-3.123 0-3.705 3.028-6.33 6.947-6.33 3.37 0 5.863 1.782 5.863 5.058 0 2.446-1.054 7.035-4.468 7.035-1.232 0-2.286-.83-2.286-2.018 0-1.742 1.307-3.43 1.307-5.225 0-1.092-.67-1.977-1.916-1.977-1.692 0-2.732 1.77-2.732 3.165 0 .774.104 1.63.476 2.336-.683 2.736-2.08 6.814-2.08 9.633 0 .87.135 1.728.224 2.6l.134.137.207-.07c2.494-3.178 2.405-3.8 3.533-7.96.61 1.077 2.182 1.658 3.43 1.658 5.254 0 7.614-4.77 7.614-9.067C26 7.987 21.755 5 17.094 5 12.017 5 7 8.15 7 13.252z" fill-rule="evenodd"></path></g></svg><span>Pinterest</span></a>';

            if($params->get('linkedin_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_linkedin" rel="noindex, nofollow"  href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{url}}&amp;title={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-linkedin-9" title="LinkedIn" alt="LinkedIn" class="at-icon at-icon-linkedin"><title id="at-svg-linkedin-9">LinkedIn</title><g><path d="M26 25.963h-4.185v-6.55c0-1.56-.027-3.57-2.175-3.57-2.18 0-2.51 1.7-2.51 3.46v6.66h-4.182V12.495h4.012v1.84h.058c.558-1.058 1.924-2.174 3.96-2.174 4.24 0 5.022 2.79 5.022 6.417v7.386zM8.23 10.655a2.426 2.426 0 0 1 0-4.855 2.427 2.427 0 0 1 0 4.855zm-2.098 1.84h4.19v13.468h-4.19V12.495z" fill-rule="evenodd"></path></g></svg><span>Linkedin</span></a>';

            //$share_tpl .= '<p><input class="os_fancybox-share__input" type="text" value="{{url_raw}}" /></p></p></div>';
             $share_tpl .= '</p></div></div>';
            //Social sharing


            $backText = $params->get("backButtonText",'back');
            $numColumns = $params->get("num_column",4);
            $minImgEnable = $params->get("minImgEnable",1);
            $minImgSize = $params->get("minImgSize",225);
            $imageMargin = $params->get("image_margin")/2;
            $loadMoreButtonText = $params->get("loadMoreButtonText", "Load More");
            $load_more_background = $params->get('load_more_background', '#12BBC5') ;                      
            $imageHover = $params->get("imageHover", "none");
            
            $imgTextPosition = $params->get("imgTextPosition", "onImage");
            $imgTextHeight = "";
            $imgTextHeight = $params->get("imgTextHeight", 40);
            $imgTextStyle = "";
            $imgTextStyle = "style='min-height:".$imgTextHeight."px; max-height:".$imgTextHeight."px; height:".$imgTextHeight."px; overflow: hidden;'";
            
            $imgMaxlengthTitle = $params->get("imgMaxlengthTitle", 100);
            $imgMaxlengthDesc = $params->get("imgMaxlengthDesc", 100);
            
            $galleryLayout = $params->get("galleryLayout", "defaultTabs");
            $masonryLayout = $params->get("masonryLayout", "default");

            ob_start();
                require self::findView($galleryLayout, "loadMore");
                $html = ob_get_contents();
            ob_end_clean();

            $catId = $input->get("catId");

            if ($imgEnd == -1) {
                $end = $imgEnd;
            }else{
                $end = $end + $numberImages;
            }
            $os_oneImgId = "";
            if($input->get("oneimg"))
            {
                $os_oneImgId = $input->get("oneimg");
            }
            $result_for_data = array_reverse($result);
            //var_dump($catId); exit;
            echo json_encode(array("success"=>true, "html"=>$html, "limEnd"=>$end, "catId"=>$catId, "os_loadMore_result"=>$result_for_data, "os_oneImgId"=>$os_oneImgId));
            return;
        
    }


    static function sortByOrdering($a,$b) {
        return $a->ordering>$b->ordering;
    }

    protected static function findView($type, $view = 'default'){
        $Path = JPATH_SITE . '/components/com_osgallery/views/'.$type. '/tmpl/' . $view . '.php';

        if (file_exists($Path)){
          return $Path;
        } else {
          echo "Bad layout path to view->".$view.", please write to admin";
          exit;
        }
    }

    public static function getSocialOptions($params) {
        $methods = array();
        if ($params->get('facebook_enable')) $methods[] = 'FacebookButton';
        if ($params->get('googleplus_enable')) $methods[] = 'GooglePlusButton';
        if ($params->get('vkontacte_enable')) $methods[] = 'VkComButton';
        if ($params->get('odnoklassniki_enable')) $methods[] = 'OdnoklassnikiButton';
        if ($params->get('twitter_enable')) $methods[] = 'TwitterButton';  
        if ($params->get('pinterest_enable')) $methods[] = 'PinterestButton';
        if ($params->get('linkedin_enable')) $methods[] = 'LinkedInButton';

        return $methods;
    }

    private static function callSocialButtonsMethods($obj, $method) {
        $method = 'get' . $method;
        if (method_exists($obj, $method)) return $obj->$method();  
    }

    public static function getButtonsArray($db, $galId, $params){   
        $buttons = array();
        $document = JFactory::getDocument();
        $url = JURI::getInstance()->toString();
        $socialPath = JURI::base() . "components/com_osgallery/assets/images/social";

        $query = "SELECT  gim.* , gal.id as galId FROM `#__os_gallery_img` as gim ".
                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                "\n WHERE cat.fk_gal_id =$galId AND gal.published".
                "\n ORDER BY cat.ordering ASC" ;
        $db->setQuery($query);
        $images =$db->loadObjectList();

        foreach ($images as $image) {
            $currentImgParams = new JRegistry;
            $currentImgParams = $currentImgParams->loadString(urldecode($image->params));
            $title = $currentImgParams->get("imgTitle",'');
            $imgLink = JURI::root().'images/com_osgallery/gal-'.$image->galId.'/original/'.$image->file_name;

            if(stripos($url, 'os_image_id') === false){

                $isSEF = JFactory::getConfig()->get('sef');
                $concat = $isSEF ? '?' : '&';
                $link =  $url . $concat . "os_image_id-" . $image->id;
                
            }else{
                $url = preg_replace("/[0-9]*$/", '', $url);
                $link =  $url.$image->id;
            }
        
            $button = new osGallerySocialButtonsHelper($link, $title, $imgLink, $socialPath);

            $methods = self::getSocialOptions($params);

            foreach ($methods as $method) {
                $buttons["os_image_id-" . $image->id][] = self::callSocialButtonsMethods($button, $method);
            }
        }


        return $buttons;
    }
    
    static function showSearchResult(){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $input = $app->input;
        
        $params = new JRegistry;
        $task = $input->getCmd('task', '');
        $view = $input->getCmd('view', '');

        //include css
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/animate.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/os-gallery.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/hover.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/font-awesome.min.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.css");

        //include js
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/jQuer/jQuerGall-2.2.4.js");
        $document->addScriptDeclaration("jQuerGall=jQuerGall.noConflict();");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.js");

        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/imagesloadedGall.pkgd.min.js");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/isotope/isotope.pkgd.min.js");
        $document->addScript(JURI::root() . "components/com_osgallery/assets/js/osGallery.main.js");

        $imgEnd = '';

        $modId = $input->getVar('moduleId', 0);
        
        // Module table
        $modTables = new  JTableModule(JFactory::getDbo());
        // Load module
        $modTables->load(array('id'=>$modId));

        $module_params = new JRegistry();
        $module_params->loadString($modTables->params);
        if(!$module_params->get('search_title', 0) && !$module_params->get('search_description', 0)){
            $app->enqueueMessage("Please select the fields to search in the module settings.", 'error');
            return;
        }
        
        $galIds = $module_params->get('gallery_list', array());
        $itemId = $module_params->get('item_id', 0);
        // 
        //var_dump($galIds); exit;
        $where = ' WHERE ';
        if(!empty($galIds)){
            $search_galIds = implode(',', $galIds);
            $where .= "cat.fk_gal_id IN ($search_galIds) AND ";
        }else{
            $search_galIds = implode(',', $galIds);
        }
        
        $search_text = $input->getVar('textsearch', '');
        $galId = $module_params->get('setting_from_gallery', '');
        
        $buttons = array();
        
            
            if($galId){
                
                //load params
                $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
                $db->setQuery($query);
                $paramsString = $db->loadResult();
                if($paramsString){
                    $params->loadString($paramsString);
                }
            }

            // LoadMore button
            $numberImagesEffect = $params->get("number_images_effect", "zoomIn");
            $numberImagesAtOnceEffect = $params->get("number_images_at_once_effect", "zoomIn");   
            $showLoadMore = $params->get("showLoadMore", 0);
            $numberImages = $params->get("number_images", 5);
            $downloadButton = $params->get("showDownload", 0);
            
            // Order by params
            $selectOrder = "drag_and_drop";
            $orderBy = "ASC";
            $selectOrder = $params->get("order", "drag_and_drop");
            $orderBy = ($selectOrder == 'drag_and_drop') ? 'ASC' : $params->get("orderBy", "ASC");
            
            if($selectOrder == 'drag_and_drop'){
                $selectOrder = "gim.ordering";
            }else{
                $selectOrder = "gim.".$selectOrder;
            }
            $order = " ORDER BY " . $selectOrder . ' ' . $orderBy . ' ';


            if($input->get('end', '') == -1){
                return;
            }

            if ($input->get('end', '')){
                $end =$input->get('end');
            }else{
                $end = 0;
            }
            $search_string = ' ';
            if($search_text != ''){
                if($module_params->get('search_title', 0) == 1 && $module_params->get('search_description', 0) == 1){
                    $search_string = " AND (gim.title LIKE '%" . $search_text . "%' OR gim.description LIKE '%" . $search_text . "%')"; 
                }elseif($module_params->get('search_title', 0) == 1 && $module_params->get('search_description', 0) == 0){
                    $search_string = " AND gim.title LIKE '%" . $search_text . "%' "; 
                }elseif ($module_params->get('search_title', 0) == 0 && $module_params->get('search_description', 0) == 1) {
                    $search_string = " AND gim.description LIKE '%" . $search_text . "%' "; 
                }else{
                    $error_text = 'No search fields selected';
                }
            }
            // ---------------
            
            if($galId){
                if (!$showLoadMore ) {
                    // getting Images
                    $query = "SELECT  gim.* , gal.id as galId FROM #__os_gallery_img as gim ".
                        "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                        "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                        "\n $where gal.published = 1 AND gim.publish=1". $search_string . ' ' . $order;
                        //"\n , cat.ordering ASC" ;
                        //var_dump($query); exit;
                    $db->setQuery($query);
                    $result =$db->loadObjectList();
                    
                } else {

                    $result = array();
                    
                    // getting Images
                    $query = "SELECT  gim.* , gal.id as galId FROM #__os_gallery_img as gim ".
                        "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                        "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                        "\n $where gal.published = 1 AND gim.publish=1 ". $search_string . ' ' . $order .
                            //"\n , cat.ordering ASC" . 
                            "\n LIMIT $end," . ($numberImages + 1);
                    $db->setQuery($query);
                    $imgArr = $db->loadObjectList();



                    $query = "SELECT  COUNT(*) as count FROM #__os_gallery_img as gim ".
                        "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                        "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                        "\n $where gal.published = 1 AND gim.publish=1 ". $search_string . ' ' . $order;
                            //"\n ORDER BY gim.ordering ASC";

                    $db->setQuery($query);
                    $img_count = $db->loadObjectList();

                    $img_count_cat = $img_count[0]->count;
                    
                    if (count($imgArr) < ($numberImages+1)) {
                        $imgEnd = -1;
                    } else {
                         unset($imgArr[count($imgArr)-1]);
                    }
                    $result = array_merge($result, $imgArr);
                    
                    
                }

                if($result){

                    $images = array();
                    foreach ($result as $image) {
                        $images[] = $image;
                    }
                    
                    //get cat params array
                    $query = "SELECT DISTINCT galImg.id,galImg.params FROM #__os_gallery_img as galImg".
                            "\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id = galImg.id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id = galCon.fk_cat_id".
                            "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                            "\n $where gal.published = 1 AND galImg.publish=1 ";
                    $db->setQuery($query);
                    $imgParamsArray = $db->loadObjectList('id');
                }else{
                    $images = array();
                    $imgParamsArray = array("1"=>(object) array("params"=>'{}'));
                    
                }
            }else{
                $app->enqueueMessage("Select gallery for menu(".$itemId.").", 'error');
                return;
            }

            if($params->get("helper_thumbnail")){
                $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancybox-thumbs.css");
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancyboxGall-thumbs.js");
            }
            if($params->get("mouse_wheel",1)){
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.mousewheel-3.0.6.pack.js");
            }


            //imgWidthThumb
            $imgWidthThumb = $params->get("imgWidth", false);
            //imgHeightThumb
            $imgHeightThumb = $params->get("imgHeight", false);
            //Background Color (complete)
            $os_fancybox_background = $params->get("fancy_box_background", "rgba(0, 0, 0, 0.75)");
            //Close by click (complete)
            $click_close = ($params->get("click_close", 1) == '1') ? '"close"' : 'false';
            //Open/Close Effect (complete)
            $open_close_effect = ($params->get("open_close_effect", "fade") == 'none') ? false : $params->get("open_close_effect", "fade");
            //Open/Close speed, ms (complete)
            $open_close_speed = $params->get("open_close_speed", 500);
            //Prev/Next Effect (complete)
            $prev_next_effect = ($params->get("prev_next_effect", "fade") == 'none') ? false : $params->get("prev_next_effect", "fade");
            // Prev/Next speed, ms (complete)
            $prev_next_speed = $params->get("prev_next_speed", 500);
            // Show Image Title
            $showImgTitle = $params->get("showImgTitle", 0);   
            // Show Image Description
            $showImgDescription = $params->get("showImgDescription", 0);   
            // Loop (complete)
            $loop = ($params->get("loop", 1) == '1')? true : $params->get("loop", 1);
            //Thumbnail Autostart
            $thumbnail_autostart = ($params->get("helper_thumbnail") == '1') ? 'true' : 'false';
            // Prev/Next Arrows (complete)
            $os_fancybox_arrows = ($params->get("os_fancybox_arrows", 1) == '1') ? true : $params->get("os_fancybox_arrows", 1);
            // Next Click (complete)
            $next_click = ($params->get("next_click", 0) == '1') ? 'next' : 'zoom';
            // Mouse Wheel (complete)
            $mouse_wheel = ($params->get("mouse_wheel", 1) == '1') ? true : 'false';
            // Autoplay (complete)
            $os_fancybox_autoplay = ($params->get("os_fancybox_autoplay", 0) == '1') ? true : 'false' ;
            // Autoplay Speed, ms(complete)
            $autoplay_speed = $params->get("autoplay_speed", 3000);
            // Thumbnails position
            $os_fancybox_thumbnail_position = $params->get("os_fancybox_thumbnail_position", "thumb_right");
            $os_fancybox_thumbnail_axis = ($params->get("os_fancybox_thumbnail_position", "thumb_right") == "thumb_bottom") ? 'x' : 'y' ;

            //Buttons panel block
            $start_slideshow_button = ($params->get("start_slideshow_button") == '1') ? 'slideShow'  : "";
            $full_screen_button = ($params->get("full_screen_button") == '1') ? 'fullScreen'  : "";
            $thumbnails_button = ($params->get("thumbnails_button") == '1') ? 'thumbs'  : "";
            $share_button = ($params->get("share_button") == '1') ? 'share'  : "";
            $download_button = ($params->get("download_button") == '1') ? 'download'  : "";
            $zoom_button = ($params->get("zoom_button") == '1') ? 'zoom'  : "";
            $left_arrow = ($params->get("left_arrow") == '1') ? 'arrowLeft'  : "";
            $right_arrow = ($params->get("right_arrow") == '1') ? 'arrowRight'  : "";
            $close_button = ($params->get("close_button") == '1') ? 'close'  : "";
            //Buttons panel block


            $infobar = ($params->get("infobar", 1) == '1') ? true : 'false';

            //Social sharing
            if( $share_button == "share" && 
                !( $params->get('facebook_enable') || $params->get('googleplus_enable') || $params->get('vkontacte_enable') 
                  || $params->get('odnoklassniki_enable') || $params->get('twitter_enable') || $params->get('pinterest_enable') 
                  || $params->get('linkedin_enable') )  
              ) $share_button = "" ;

            $share_tpl = '<div class="os_fancybox-share"><div class="container"><h1>{{SHARE}}</h1><p class="os_fancybox-share__links">';

            if($params->get('facebook_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_facebook" rel="noindex, nofollow"  href="https://www.facebook.com/sharer.php?u={{url}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-facebook-2" title="Facebook" alt="Facebook" class="at-icon at-icon-facebook"><title id="at-svg-facebook-2">Facebook</title><g><path d="M22 5.16c-.406-.054-1.806-.16-3.43-.16-3.4 0-5.733 1.825-5.733 5.17v2.882H9v3.913h3.837V27h4.604V16.965h3.823l.587-3.913h-4.41v-2.5c0-1.123.347-1.903 2.198-1.903H22V5.16z" fill-rule="evenodd"></path></g></svg></span><span>Facebook</span></a>';
                    
            if($params->get('googleplus_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_googleplus" rel="noindex, nofollow"  href="https://plus.google.com/share?url={{url}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-google_plusone_share-8" title="Google+" alt="Google+" class="at-icon at-icon-google_plusone_share"><title id="at-svg-google_plusone_share-8">Google+</title><g><path d="M12 15v2.4h3.97c-.16 1.03-1.2 3.02-3.97 3.02-2.39 0-4.34-1.98-4.34-4.42s1.95-4.42 4.34-4.42c1.36 0 2.27.58 2.79 1.08l1.9-1.83C15.47 9.69 13.89 9 12 9c-3.87 0-7 3.13-7 7s3.13 7 7 7c4.04 0 6.72-2.84 6.72-6.84 0-.46-.05-.81-.11-1.16H12zm15 0h-2v-2h-2v2h-2v2h2v2h2v-2h2v-2z" fill-rule="evenodd"></path></g></svg></span><span>Google +</span></a>';

            if($params->get('vkontacte_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_vkontacte" rel="noindex, nofollow"  href="http://vk.com/share.php?url={{url}}&amp;title={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-vk-170" title="Vkontakte" alt="Vkontakte" class="at-icon at-icon-vk"><title id="at-svg-vk-170">Vkontakte</title><g><path d="M26.712 10.96s-.167-.48-1.21-.348l-3.447.024a.785.785 0 0 0-.455.072s-.204.108-.3.37a22.1 22.1 0 0 1-1.28 2.695c-1.533 2.61-2.156 2.754-2.407 2.587-.587-.372-.43-1.51-.43-2.323 0-2.54.382-3.592-.756-3.868-.37-.084-.646-.144-1.616-.156-1.232-.012-2.274 0-2.86.287-.396.193-.695.624-.515.648.227.036.742.143 1.017.515 0 0 .3.49.347 1.568.13 2.982-.48 3.353-.48 3.353-.466.252-1.28-.167-2.478-2.634 0 0-.694-1.222-1.233-2.563-.097-.25-.288-.383-.288-.383s-.216-.168-.527-.216l-3.28.024c-.504 0-.683.228-.683.228s-.18.19-.012.587c2.562 6.022 5.483 9.04 5.483 9.04s2.67 2.79 5.7 2.597h1.376c.418-.035.634-.263.634-.263s.192-.214.18-.61c-.024-1.843.838-2.12.838-2.12.838-.262 1.915 1.785 3.065 2.575 0 0 .874.6 1.532.467l3.064-.048c1.617-.01.85-1.352.85-1.352-.06-.108-.442-.934-2.286-2.647-1.916-1.784-1.665-1.496.658-4.585 1.413-1.88 1.976-3.03 1.796-3.52z" fill-rule="evenodd"></path></g></svg></span><span>Vkontakte</span></a>';

            if($params->get('odnoklassniki_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_odnoklassniki" rel="noindex, nofollow"  href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={{url}}&amp;st.comments={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-odnoklassniki_ru-116" title="Odnoklassniki" alt="Odnoklassniki" class="at-icon at-icon-odnoklassniki_ru"><title id="at-svg-odnoklassniki_ru-116">Odnoklassniki</title><g><path d="M16.5 16.15A6.15 6.15 0 0 0 22.65 10c0-3.39-2.75-6.14-6.15-6.14-3.4 0-6.15 2.75-6.15 6.14.01 3.4 2.76 6.15 6.15 6.15zm0-9.17c1.67 0 3.02 1.35 3.02 3.02s-1.35 3.02-3.02 3.02-3.02-1.35-3.02-3.02 1.35-3.02 3.02-3.02zm7.08 9.92c-.35-.7-1.31-1.28-2.58-.27-1.73 1.36-4.5 1.36-4.5 1.36s-2.77 0-4.5-1.36c-1.28-1.01-2.24-.43-2.59.27-.6 1.22.08 1.8 1.62 2.79 1.32.85 3.13 1.16 4.3 1.28l-.98.98c-1.38 1.37-2.7 2.7-3.62 3.62-.55.55-.55 1.438 0 1.99l.17.17c.55.55 1.44.55 1.99 0l3.62-3.622 3.62 3.62c.55.55 1.44.55 1.99 0l.17-.17c.55-.55.55-1.44 0-1.99l-3.62-3.62-.98-.98c1.17-.12 2.96-.438 4.27-1.28 1.55-.988 2.23-1.58 1.62-2.788z"></path></g></svg></span><span>Odnoklassniki</span></a>';

            if($params->get('twitter_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_twitter" rel="noindex, nofollow"  href="https://twitter.com/share?url={{url}}&amp;text={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-twitter-3" title="Twitter" alt="Twitter" class="at-icon at-icon-twitter"><title id="at-svg-twitter-3">Twitter</title><g><path d="M27.996 10.116c-.81.36-1.68.602-2.592.71a4.526 4.526 0 0 0 1.984-2.496 9.037 9.037 0 0 1-2.866 1.095 4.513 4.513 0 0 0-7.69 4.116 12.81 12.81 0 0 1-9.3-4.715 4.49 4.49 0 0 0-.612 2.27 4.51 4.51 0 0 0 2.008 3.755 4.495 4.495 0 0 1-2.044-.564v.057a4.515 4.515 0 0 0 3.62 4.425 4.52 4.52 0 0 1-2.04.077 4.517 4.517 0 0 0 4.217 3.134 9.055 9.055 0 0 1-5.604 1.93A9.18 9.18 0 0 1 6 23.85a12.773 12.773 0 0 0 6.918 2.027c8.3 0 12.84-6.876 12.84-12.84 0-.195-.005-.39-.014-.583a9.172 9.172 0 0 0 2.252-2.336" fill-rule="evenodd"></path></g></svg></span><span>Twitter</span></a>';

            if($params->get('pinterest_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_pinterest" rel="noindex, nofollow"  href="http://pinterest.com/pin/create/button/?url={{url}}&amp;description={{descr}}&amp;media={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-pinterest_share-6" title="Pinterest" alt="Pinterest" class="at-icon at-icon-pinterest_share"><title id="at-svg-pinterest_share-6">Pinterest</title><g><path d="M7 13.252c0 1.81.772 4.45 2.895 5.045.074.014.178.04.252.04.49 0 .772-1.27.772-1.63 0-.428-1.174-1.34-1.174-3.123 0-3.705 3.028-6.33 6.947-6.33 3.37 0 5.863 1.782 5.863 5.058 0 2.446-1.054 7.035-4.468 7.035-1.232 0-2.286-.83-2.286-2.018 0-1.742 1.307-3.43 1.307-5.225 0-1.092-.67-1.977-1.916-1.977-1.692 0-2.732 1.77-2.732 3.165 0 .774.104 1.63.476 2.336-.683 2.736-2.08 6.814-2.08 9.633 0 .87.135 1.728.224 2.6l.134.137.207-.07c2.494-3.178 2.405-3.8 3.533-7.96.61 1.077 2.182 1.658 3.43 1.658 5.254 0 7.614-4.77 7.614-9.067C26 7.987 21.755 5 17.094 5 12.017 5 7 8.15 7 13.252z" fill-rule="evenodd"></path></g></svg></span><span>Pinterest</span></a>';

            if($params->get('linkedin_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_linkedin" rel="noindex, nofollow"  href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{url}}&amp;title={{descr}}&amp;image={{media}}"><span class="bg_os_icons"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-linkedin-9" title="LinkedIn" alt="LinkedIn" class="at-icon at-icon-linkedin"><title id="at-svg-linkedin-9">LinkedIn</title><g><path d="M26 25.963h-4.185v-6.55c0-1.56-.027-3.57-2.175-3.57-2.18 0-2.51 1.7-2.51 3.46v6.66h-4.182V12.495h4.012v1.84h.058c.558-1.058 1.924-2.174 3.96-2.174 4.24 0 5.022 2.79 5.022 6.417v7.386zM8.23 10.655a2.426 2.426 0 0 1 0-4.855 2.427 2.427 0 0 1 0 4.855zm-2.098 1.84h4.19v13.468h-4.19V12.495z" fill-rule="evenodd"></path></g></svg></span><span>Linkedin</span></a>';

            //$share_tpl .= '<p><input class="os_fancybox-share__input" type="text" value="{{url_raw}}" /></p></p></div>';
             $share_tpl .= '</p></div></div>';
            //Social sharing


            $backText = $params->get("backButtonText",'back');
            $numColumns = $params->get("num_column",4);
            $minImgEnable = $params->get("minImgEnable",1);
            $minImgSize = $params->get("minImgSize",225);
            $imageMargin = $params->get("image_margin")/2;
            $loadMoreButtonText = $params->get("loadMoreButtonText", "Load More");
            $load_more_background = $params->get('load_more_background', '#12BBC5') ;                      
            $imageHover = $params->get("imageHover", "none");
            
            $imgTextPosition = $params->get("imgTextPosition", "onImage");
            $imgTextHeight = "";
            $imgTextHeight = $params->get("imgTextHeight", 40);
            $imgTextStyle = "";
            $imgTextStyle = "style='min-height:".$imgTextHeight."px; max-height:".$imgTextHeight."px; height:".$imgTextHeight."px; overflow: hidden;'";
            
            $imgMaxlengthTitle = $params->get("imgMaxlengthTitle", 100);
            $imgMaxlengthDesc = $params->get("imgMaxlengthDesc", 100);
            
            $galleryLayout = "searchResult";
            $masonryLayout = $params->get("masonryLayout", "default");
            //var_dump($galleryLayout); exit;

            require self::findView($galleryLayout);

        
    }
    
    static function showSearchResultAjax(){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $input = $app->input;
        
        $params = new JRegistry;
        $view = $input->getCmd('view', '');

        //include css
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/os-gallery.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/hover.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/font-awesome.min.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.css");

        //include js
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/jQuer/jQuerGall-2.2.4.js");
        $document->addScriptDeclaration("jQuerGall=jQuerGall.noConflict();");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquer.os_fancyboxGall.js");

        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/imagesloadedGall.pkgd.min.js");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/isotope/isotope.pkgd.min.js");
        $document->addScript(JURI::root() . "components/com_osgallery/assets/js/osGallery.main.js");
        
        $imgEnd = '';
        $modId = $input->getVar('moduleId', 0);
        
        // Module table
        $modTables = new  JTableModule(JFactory::getDbo());
        // Load module
        $modTables->load(array('id'=>$modId));

        $module_params = new JRegistry();
        $module_params->loadString($modTables->params);
        $galIds = $module_params->get('gallery_list', array());
        
        $galId = $input->get('galId');
        $search_text = $input->getVar('searchText');
        //var_dump($search_text); exit;
        $where = ' WHERE ';
        if($galIds != ''){
            $search_galIds = implode(',',$galIds);
            $where .= "cat.fk_gal_id IN ($search_galIds) AND ";
        }

        $buttons = array();

        if($galId){
            //load params
            $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
            $db->setQuery($query);
            $paramsString = $db->loadResult();
            if($paramsString){
                $params->loadString($paramsString);
            }
        }

        // LoadMore button
        $numberImagesEffect = $params->get("number_images_effect", "zoomIn");
        $numberImagesAtOnceEffect = $params->get("number_images_at_once_effect", "zoomIn");
        $numberImages = $params->get("number_images_at_once", 5);
        $showLoadMore = $params->get("showLoadMore", 0);
        $downloadButton = $params->get("showDownload", 0);

        // Order by params
        $selectOrder = "drag_and_drop";
        $orderBy = "ASC";
        $selectOrder = $params->get("order", "drag_and_drop");
        $orderBy = ($selectOrder == 'drag_and_drop') ? 'ASC' : $params->get("orderBy", "ASC");

        if($selectOrder == 'drag_and_drop'){
            $selectOrder = "gim.ordering";
        }else{
            $selectOrder = "gim.".$selectOrder;
        }
        $order = " ORDER BY " . $selectOrder . ' ' . $orderBy . ' ';

        if($input->get('end', '') == -1){
            return;
        }

        if ($input->get('end', '')){
            $end =$input->get('end');
        }
        else{
            $end = 0;
        }

        if($search_text != ''){
            if($module_params->get('search_title', 0) == 1 && $module_params->get('search_description', 0) == 1){
                $search_string = " AND (gim.title LIKE '%" . $search_text . "%' OR gim.description LIKE '%" . $search_text . "%')"; 
            }elseif($module_params->get('search_title', 0) == 1 && $module_params->get('search_description', 0) == 0){
                $search_string = " AND gim.title LIKE '%" . $search_text . "%' "; 
            }elseif ($module_params->get('search_title', 0) == 0 && $module_params->get('search_description', 0) == 1) {
                $search_string = " AND gim.description LIKE '%" . $search_text . "%' "; 
            }else{
                $error_text = 'No search fields selected';
            }
        }else{
            $search_string = ' ';
        }


            if (!$showLoadMore ) {
                // getting Images
                $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId FROM #__os_gallery_img as gim ".
                        "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                        "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                        "\n $where gal.published=1 AND gim.publish=1 " . $search_string . ' ' . $order;
                        //"\n ORDER BY cat.ordering ASC" ;
                $db->setQuery($query);
                $result =$db->loadObjectList();

            } else {

                $result = array();
                if ($input->get("oneimg")){
                    $os_oneImgId = $input->get("oneimg");

                    // getting Images
                    $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                            "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                            "\n $where gal.published=1 AND gim.publish=1 AND gim.id =$os_oneImgId " . $search_string;

                    $db->setQuery($query);
                    $imgArr = $db->loadObjectList();


                    $result = array_merge($result, $imgArr);

                } else {



                    // getting Images
                    $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                            "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                            "\n $where gal.published=1 AND gim.publish=1 " . $search_string. ' ' . $order .
                            //"\n ORDER BY gim.ordering ASC" . 
                            "\n LIMIT $end," . ($numberImages + 1);

                    $db->setQuery($query);
                    $imgArr = $db->loadObjectList();

                    if (count($imgArr) < ($numberImages+1)) {
                        $imgEnd = -1;
                    } else {
                         unset($imgArr[count($imgArr)-1]);
                    }
                    $result = array_merge($result, $imgArr);

                }

            }

            if($result){

                $images = array();
                foreach ($result as $image) {
                    $images[] = $image;
                }


                //get cat params array
                $query = "SELECT DISTINCT galImg.id,galImg.params FROM #__os_gallery_img as galImg".
                        "\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id = galImg.id".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id = galCon.fk_cat_id".
                        "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                        "\n $where gal.published=1 AND gim.publish=1 ";
                $db->setQuery($query);
                $imgParamsArray = $db->loadObjectList('id');
            }else{
                $images = array();
                $imgParamsArray = array("1"=>(object) array("params"=>'{}'));

            }
            

        if($params->get("helper_thumbnail")){
            $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancybox-thumbs.css");
            $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.os_fancyboxGall-thumbs.js");
        }
        if($params->get("mouse_wheel",1)){
            $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquer.mousewheel-3.0.6.pack.js");
        }


         //imgWidthThumb
        $imgWidthThumb = $params->get("imgWidth", false);
        //imgHeightThumb
        $imgHeightThumb = $params->get("imgHeight", false);
        //Background Color (complete)
        $os_fancybox_background = $params->get("fancy_box_background", "rgba(0, 0, 0, 0.75)");
        //Close by click (complete)
        $click_close = ($params->get("click_close", 1) == '1') ? '"close"' : 'false';
        //Open/Close Effect (complete)
        $open_close_effect = ($params->get("open_close_effect", "fade") == 'none') ? false : $params->get("open_close_effect", "fade");
        //Open/Close speed, ms (complete)
        $open_close_speed = $params->get("open_close_speed", 500);
        //Prev/Next Effect (complete)
        $prev_next_effect = ($params->get("prev_next_effect", "fade") == 'none') ? false : $params->get("prev_next_effect", "fade");
        // Prev/Next speed, ms (complete)
        $prev_next_speed = $params->get("prev_next_speed", 500);
        // Show Image Title
        $showImgTitle = $params->get("showImgTitle", 0);   
        // Show Image Description
        $showImgDescription = $params->get("showImgDescription", 0);   
        // Loop (complete)
        $loop = ($params->get("loop", 1) == '1')? true : $params->get("loop", 1);
        //Thumbnail Autostart
        $thumbnail_autostart = ($params->get("helper_thumbnail") == '1') ? 'true' : 'false';
        // Prev/Next Arrows (complete)
        $os_fancybox_arrows = ($params->get("os_fancybox_arrows", 1) == '1') ? true : $params->get("os_fancybox_arrows", 1);
        // Next Click (complete)
        $next_click = ($params->get("next_click", 0) == '1') ? 'next' : 'zoom';
        // Mouse Wheel (complete)
        $mouse_wheel = ($params->get("mouse_wheel", 1) == '1') ? true : 'false';
        // Autoplay (complete)
        $os_fancybox_autoplay = ($params->get("os_fancybox_autoplay", 0) == '1') ? true : 'false' ;
        // Autoplay Speed, ms(complete)
        $autoplay_speed = $params->get("autoplay_speed", 3000);
        // Thumbnails position
        $os_fancybox_thumbnail_position = $params->get("os_fancybox_thumbnail_position", "thumb_right");
        $os_fancybox_thumbnail_axis = ($params->get("os_fancybox_thumbnail_position", "thumb_right") == "thumb_bottom") ? 'x' : 'y' ;

        //Buttons panel block
        $start_slideshow_button = ($params->get("start_slideshow_button") == '1') ? 'slideShow'  : "";
        $full_screen_button = ($params->get("full_screen_button") == '1') ? 'fullScreen'  : "";
        $thumbnails_button = ($params->get("thumbnails_button") == '1') ? 'thumbs'  : "";
        $share_button = ($params->get("share_button") == '1') ? 'share'  : "";
        $download_button = ($params->get("download_button") == '1') ? 'download'  : "";
        $zoom_button = ($params->get("zoom_button") == '1') ? 'zoom'  : "";
        $left_arrow = ($params->get("left_arrow") == '1') ? 'arrowLeft'  : "";
        $right_arrow = ($params->get("right_arrow") == '1') ? 'arrowRight'  : "";
        $close_button = ($params->get("close_button") == '1') ? 'close'  : "";
        //Buttons panel block

        $infobar = ($params->get("infobar", 1) == '1') ? true : 'false';

        //Social sharing
        if( $share_button == "share" && 
            !( $params->get('facebook_enable') || $params->get('googleplus_enable') || $params->get('vkontacte_enable') 
              || $params->get('odnoklassniki_enable') || $params->get('twitter_enable') || $params->get('pinterest_enable') 
              || $params->get('linkedin_enable') )  
          ) $share_button = "" ;

        $share_tpl = '<div class="os_fancybox-share"><div class="container"><h1>{{SHARE}}</h1><p class="os_fancybox-share__links">';

        if($params->get('facebook_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_facebook" rel="noindex, nofollow"  href="https://www.facebook.com/sharer.php?u={{url}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-facebook-2" title="Facebook" alt="Facebook" class="at-icon at-icon-facebook"><title id="at-svg-facebook-2">Facebook</title><g><path d="M22 5.16c-.406-.054-1.806-.16-3.43-.16-3.4 0-5.733 1.825-5.733 5.17v2.882H9v3.913h3.837V27h4.604V16.965h3.823l.587-3.913h-4.41v-2.5c0-1.123.347-1.903 2.198-1.903H22V5.16z" fill-rule="evenodd"></path></g></svg><span>Facebook</span></a>';

        if($params->get('googleplus_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_googleplus" rel="noindex, nofollow"  href="https://plus.google.com/share?url={{url}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-google_plusone_share-8" title="Google+" alt="Google+" class="at-icon at-icon-google_plusone_share"><title id="at-svg-google_plusone_share-8">Google+</title><g><path d="M12 15v2.4h3.97c-.16 1.03-1.2 3.02-3.97 3.02-2.39 0-4.34-1.98-4.34-4.42s1.95-4.42 4.34-4.42c1.36 0 2.27.58 2.79 1.08l1.9-1.83C15.47 9.69 13.89 9 12 9c-3.87 0-7 3.13-7 7s3.13 7 7 7c4.04 0 6.72-2.84 6.72-6.84 0-.46-.05-.81-.11-1.16H12zm15 0h-2v-2h-2v2h-2v2h2v2h2v-2h2v-2z" fill-rule="evenodd"></path></g></svg><span>Google +</span></a>';

        if($params->get('vkontacte_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_vkontacte" rel="noindex, nofollow"  href="http://vk.com/share.php?url={{url}}&amp;title={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-vk-170" title="Vkontakte" alt="Vkontakte" class="at-icon at-icon-vk"><title id="at-svg-vk-170">Vkontakte</title><g><path d="M26.712 10.96s-.167-.48-1.21-.348l-3.447.024a.785.785 0 0 0-.455.072s-.204.108-.3.37a22.1 22.1 0 0 1-1.28 2.695c-1.533 2.61-2.156 2.754-2.407 2.587-.587-.372-.43-1.51-.43-2.323 0-2.54.382-3.592-.756-3.868-.37-.084-.646-.144-1.616-.156-1.232-.012-2.274 0-2.86.287-.396.193-.695.624-.515.648.227.036.742.143 1.017.515 0 0 .3.49.347 1.568.13 2.982-.48 3.353-.48 3.353-.466.252-1.28-.167-2.478-2.634 0 0-.694-1.222-1.233-2.563-.097-.25-.288-.383-.288-.383s-.216-.168-.527-.216l-3.28.024c-.504 0-.683.228-.683.228s-.18.19-.012.587c2.562 6.022 5.483 9.04 5.483 9.04s2.67 2.79 5.7 2.597h1.376c.418-.035.634-.263.634-.263s.192-.214.18-.61c-.024-1.843.838-2.12.838-2.12.838-.262 1.915 1.785 3.065 2.575 0 0 .874.6 1.532.467l3.064-.048c1.617-.01.85-1.352.85-1.352-.06-.108-.442-.934-2.286-2.647-1.916-1.784-1.665-1.496.658-4.585 1.413-1.88 1.976-3.03 1.796-3.52z" fill-rule="evenodd"></path></g></svg><span>Vkontakte</span></a>';

        if($params->get('odnoklassniki_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_odnoklassniki" rel="noindex, nofollow"  href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={{url}}&amp;st.comments={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-odnoklassniki_ru-116" title="Odnoklassniki" alt="Odnoklassniki" class="at-icon at-icon-odnoklassniki_ru"><title id="at-svg-odnoklassniki_ru-116">Odnoklassniki</title><g><path d="M16.5 16.15A6.15 6.15 0 0 0 22.65 10c0-3.39-2.75-6.14-6.15-6.14-3.4 0-6.15 2.75-6.15 6.14.01 3.4 2.76 6.15 6.15 6.15zm0-9.17c1.67 0 3.02 1.35 3.02 3.02s-1.35 3.02-3.02 3.02-3.02-1.35-3.02-3.02 1.35-3.02 3.02-3.02zm7.08 9.92c-.35-.7-1.31-1.28-2.58-.27-1.73 1.36-4.5 1.36-4.5 1.36s-2.77 0-4.5-1.36c-1.28-1.01-2.24-.43-2.59.27-.6 1.22.08 1.8 1.62 2.79 1.32.85 3.13 1.16 4.3 1.28l-.98.98c-1.38 1.37-2.7 2.7-3.62 3.62-.55.55-.55 1.438 0 1.99l.17.17c.55.55 1.44.55 1.99 0l3.62-3.622 3.62 3.62c.55.55 1.44.55 1.99 0l.17-.17c.55-.55.55-1.44 0-1.99l-3.62-3.62-.98-.98c1.17-.12 2.96-.438 4.27-1.28 1.55-.988 2.23-1.58 1.62-2.788z"></path></g></svg><span>Odnoklassniki</span></a>';

        if($params->get('twitter_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_twitter" rel="noindex, nofollow"  href="https://twitter.com/share?url={{url}}&amp;text={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-twitter-3" title="Twitter" alt="Twitter" class="at-icon at-icon-twitter"><title id="at-svg-twitter-3">Twitter</title><g><path d="M27.996 10.116c-.81.36-1.68.602-2.592.71a4.526 4.526 0 0 0 1.984-2.496 9.037 9.037 0 0 1-2.866 1.095 4.513 4.513 0 0 0-7.69 4.116 12.81 12.81 0 0 1-9.3-4.715 4.49 4.49 0 0 0-.612 2.27 4.51 4.51 0 0 0 2.008 3.755 4.495 4.495 0 0 1-2.044-.564v.057a4.515 4.515 0 0 0 3.62 4.425 4.52 4.52 0 0 1-2.04.077 4.517 4.517 0 0 0 4.217 3.134 9.055 9.055 0 0 1-5.604 1.93A9.18 9.18 0 0 1 6 23.85a12.773 12.773 0 0 0 6.918 2.027c8.3 0 12.84-6.876 12.84-12.84 0-.195-.005-.39-.014-.583a9.172 9.172 0 0 0 2.252-2.336" fill-rule="evenodd"></path></g></svg><span>Twitter</span></a>';

        if($params->get('pinterest_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_pinterest" rel="noindex, nofollow"  href="http://pinterest.com/pin/create/button/?url={{url}}&amp;description={{descr}}&amp;media={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-pinterest_share-6" title="Pinterest" alt="Pinterest" class="at-icon at-icon-pinterest_share"><title id="at-svg-pinterest_share-6">Pinterest</title><g><path d="M7 13.252c0 1.81.772 4.45 2.895 5.045.074.014.178.04.252.04.49 0 .772-1.27.772-1.63 0-.428-1.174-1.34-1.174-3.123 0-3.705 3.028-6.33 6.947-6.33 3.37 0 5.863 1.782 5.863 5.058 0 2.446-1.054 7.035-4.468 7.035-1.232 0-2.286-.83-2.286-2.018 0-1.742 1.307-3.43 1.307-5.225 0-1.092-.67-1.977-1.916-1.977-1.692 0-2.732 1.77-2.732 3.165 0 .774.104 1.63.476 2.336-.683 2.736-2.08 6.814-2.08 9.633 0 .87.135 1.728.224 2.6l.134.137.207-.07c2.494-3.178 2.405-3.8 3.533-7.96.61 1.077 2.182 1.658 3.43 1.658 5.254 0 7.614-4.77 7.614-9.067C26 7.987 21.755 5 17.094 5 12.017 5 7 8.15 7 13.252z" fill-rule="evenodd"></path></g></svg><span>Pinterest</span></a>';

        if($params->get('linkedin_enable')) $share_tpl .= '<a class="os_fancybox-share__button os_fancybox-share__button--fb os_linkedin" rel="noindex, nofollow"  href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{url}}&amp;title={{descr}}&amp;image={{media}}"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" version="1.1" role="img" aria-labelledby="at-svg-linkedin-9" title="LinkedIn" alt="LinkedIn" class="at-icon at-icon-linkedin"><title id="at-svg-linkedin-9">LinkedIn</title><g><path d="M26 25.963h-4.185v-6.55c0-1.56-.027-3.57-2.175-3.57-2.18 0-2.51 1.7-2.51 3.46v6.66h-4.182V12.495h4.012v1.84h.058c.558-1.058 1.924-2.174 3.96-2.174 4.24 0 5.022 2.79 5.022 6.417v7.386zM8.23 10.655a2.426 2.426 0 0 1 0-4.855 2.427 2.427 0 0 1 0 4.855zm-2.098 1.84h4.19v13.468h-4.19V12.495z" fill-rule="evenodd"></path></g></svg><span>Linkedin</span></a>';

        //$share_tpl .= '<p><input class="os_fancybox-share__input" type="text" value="{{url_raw}}" /></p></p></div>';
         $share_tpl .= '</p></div></div>';
        //Social sharing


        $backText = $params->get("backButtonText",'back');
        $numColumns = $params->get("num_column",4);
        $minImgEnable = $params->get("minImgEnable",1);
        $minImgSize = $params->get("minImgSize",225);
        $imageMargin = $params->get("image_margin")/2;
        $loadMoreButtonText = $params->get("loadMoreButtonText", "Load More");
        $load_more_background = $params->get('load_more_background', '#12BBC5') ;                      
        $imageHover = $params->get("imageHover", "none");
        
        $imgTextPosition = $params->get("imgTextPosition", "onImage");
        $imgTextHeight = "";
        $imgTextHeight = $params->get("imgTextHeight", 40);
        $imgTextStyle = "";
        $imgTextStyle = "style='min-height:".$imgTextHeight."px; max-height:".$imgTextHeight."px; height:".$imgTextHeight."px; overflow: hidden;'";

        $imgMaxlengthTitle = $params->get("imgMaxlengthTitle", 100);
        $imgMaxlengthDesc = $params->get("imgMaxlengthDesc", 100);
            
        $galleryLayout = "searchResult";
        $masonryLayout = $params->get("masonryLayout", "default");

        ob_start();
            require self::findView($galleryLayout, "loadMore");
            $html = ob_get_contents();
        ob_end_clean();

        $catId = $input->get("catId");

        if ($imgEnd == -1) {
            $end = $imgEnd;
        }else{
            $end = $end + $numberImages;
        }
        $os_oneImgId = "";
        if($input->get("oneimg"))
        {
            $os_oneImgId = $input->get("oneimg");
        }
        $result_for_data = array_reverse($result);
        //var_dump($catId); exit;
        echo json_encode(array("success"=>true, "html"=>$html, "limEnd"=>$end, "catId"=>'1', "os_loadMore_result"=>$result_for_data, "os_oneImgId"=>$os_oneImgId));
        return;
        
    }
}