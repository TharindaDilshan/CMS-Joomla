<?php
/**
* @title		banner image slider module
* @website		http://www.joombest.com
* @copyright	Copyright (C) 2016 joombest.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

    // no direct access
    defined('_JEXEC') or die;
?>
<script>
jQuery.noConflict();
</script>



<link rel="stylesheet" href="<?php echo $PathConfig_live_site; ?>/modules/mod_banner_image_slider/tmpl/BannerimagSldeh/css/default.css" />

<?php
if ($enableQuery == 1) {?>
	<script type="text/javascript" src="<?php echo $PathConfig_live_site; ?>/modules/mod_banner_image_slider/tmpl/BannerimagSldeh/js/jquery-1.11.3.min.js"></script>
<?php }?>
	
<script type="text/javascript" src="<?php echo $PathConfig_live_site; ?>/modules/mod_banner_image_slider/tmpl/BannerimagSldeh/js/bannerslider.mini.js"></script>
		

<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/jssor.slider.mini.js"></script>
	
	
	
<div id="banne_bound_jsoo" value="9">	
 <div id="jssor_banneimgslder" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 900px; height: 400px; overflow: hidden; visibility: hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
            <div style="position:absolute;display:block;background:url('<?php echo $PathConfig_live_site; ?>/modules/mod_banner_image_slider/tmpl/BannerimagSldeh/css/img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
        </div>
        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 900px; height: 400px; overflow: hidden;">
			 <?php
			 $countdataitem = 0;
foreach($data as $index=>$value)
{?>
            <div data-p="112.50" style="display: none;">
                <img data-u="image" src="<?php echo JURI::root().$value['image'] ?>" />
                <div data-u="caption" data-t="0" data-hwa="1" style="position: absolute; top: 320px; left: 30px; width: 350px; height: 30px; background-color: rgba(235,81,0,0.5); font-size: 20px; color: #ffffff; line-height: 30px; text-align: center;"><?php echo $value['title'] ?></div>
            </div>
<?php
$countdataitem++;
} ?>           
        </div>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb01" style="bottom:16px;right:16px;">
            <div data-u="prototype" style="width:12px;height:12px;"></div>
        </div>
        <!-- Arrow Navigator -->
        <span data-u="arrowleft" class="jssora02l" style="top:0px;left:8px;width:55px;height:55px;" data-autocenter="2"></span>
        <span data-u="arrowright" class="jssora02r" style="top:0px;right:8px;width:55px;height:55px;" data-autocenter="2"></span>
    </div>
	</div>
	
	
	

	
 
	
	

	
	  

	
