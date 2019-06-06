<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8715 2015-02-17 08:45:23Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

$appWeb      = new JApplicationWeb;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$task = $app->input->getCmd('task', '');

if($task !== 'ap') {
echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));

JFactory::getDocument()->addStyleSheet("templates/".$template."/css/easyzoom.css");

vmJsApi::addJScript("/templates/".$template."/js/easyzoom.js",false,false);
vmJsApi::addJScript("/templates/".$template."/js/jssor.slider.mini.js",false,false);

vmJsApi::addJScript("/templates/".$template."/js/jquery.validate.min.js",false,false);
vmJsApi::addJScript("/templates/".$template."/js/additional-methods.min.js",false,false);

if(vRequest::getInt('print',false)){ ?>
<body onload="javascript:print();">
<?php } ?>
<div id="com_virtuemart">
<div class="productdetails-view productdetails productdetails-default">
<script>
	jQuery.extend(Virtuemart, {
		product: function(carts){
			carts.each(function(){
				var cart = jQuery(this),
				quantityInput=cart.find('input[name="quantity[]"]'),
				plus   = cart.find('.quantity-plus'),
				minus  = cart.find('.quantity-minus'),
				select = cart.find('select:not(.no-vm-bind)'),
				radio = cart.find('input:radio:not(.no-vm-bind)'),
				virtuemart_product_id = cart.find('input[name="virtuemart_product_id[]"]').val(),
				quantity = cart.find('.quantity-input');

				var Ste = parseInt(quantityInput.attr("step"));

				//Fallback for layouts lower than 2.0.18b
				if(isNaN(Ste)){
					Ste = 1;
				}

        jQuery(plus).off('click', Virtuemart.incrQuantity);
        jQuery(plus).on('click', {cart:cart}, Virtuemart.incrQuantity);

        jQuery(minus).off('click', Virtuemart.decrQuantity);
        jQuery(minus).on('click', {cart:cart},Virtuemart.decrQuantity);

        jQuery(select).off('change', Virtuemart.eventsetproducttype);
        jQuery(select).on('change', {cart:cart,virtuemart_product_id:virtuemart_product_id},Virtuemart.eventsetproducttype);

        jQuery(radio).off('change', Virtuemart.eventsetproducttype);
        jQuery(radio).on('change', {cart:cart,virtuemart_product_id:virtuemart_product_id},Virtuemart.eventsetproducttype);

        jQuery(quantity).off('keyup', Virtuemart.eventsetproducttype);
        jQuery(quantity).on('keyup', {cart:cart,virtuemart_product_id:virtuemart_product_id},Virtuemart.eventsetproducttype);

        this.action ="#";
        addtocart = cart.find('.addtocart-button[name="addtocart"]');
        jQuery(addtocart).off('click',Virtuemart.addtocart);
        jQuery(addtocart).on('click',{cart:cart},Virtuemart.addtocart);
			});
		}
	},{
		updateDynamicUpdateListeners: function() {
      jQuery('*[data-dynamic-update=1]').each(function(i, el) {
        var nodeName = el.nodeName;
        el = jQuery(el);
        switch (nodeName) {
          case 'A':
				    el[0].onclick = null;
            el.off('click',Virtuemart.updL);
            el.on('click',Virtuemart.updL);
            jQuery('body>div.tooltip').remove();
            break;
          default:
				    el[0].onchange = null;
            el.off('change',Virtuemart.upd);
            el.on('change',Virtuemart.upd);
        }
      });
    }
	},{
    addtocart: function (e){
      var targ;
      if (!e) e = window.event;
      e.preventDefault();
      if(e.hasOwnProperty('stopSendtocart') && e.stopSendtocart == true){
          return false;
      }
      if (e.target) targ = e.target;
      else if (e.srcElement) targ = e.srcElement;
      if (targ.nodeType == 3) // defeat Safari bug
        targ = targ.parentNode;

      if ((jQuery(targ).prop("type") == "submit" ||  jQuery(targ).prop("type") == "image" )) {
        var dataArray = e.data.cart.serializeArray(),
        len = dataArray.length,
        dataObj = {};

        for (i=0; i<len; i++) {
          dataObj[dataArray[i].name] = dataArray[i].value;
        }
        if(parseInt(dataObj['quantity[]'])>0) Virtuemart.sendtocart(e.data.cart);
        return false;
      }
    }
  });
  jQuery(document).ready(function ($) {

    var options = {
      $AutoPlay: false,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
      $Loop: 0,
      $PauseOnHover: 1,                                //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

      $DragOrientation: 0,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)
      $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
      $SlideDuration: 600,                                //Specifies default duration (swipe) for slide in milliseconds

      $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
        $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
        $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
        $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
      },

      $ThumbnailNavigatorOptions: {                       //[Optional] Options to specify and enable thumbnail navigator or not
        $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
        $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
        $ActionMode: 1,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
        $Lanes: 1,                                      //[Optional] Specify lanes to arrange thumbnails, default value is 1
        $SpacingY: 10,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
        $DisplayPieces: 5,                             //[Optional] Number of pieces to display, default value is 1
        $ParkingPosition: 0,                          //[Optional] The offset position to park thumbnail
        $Orientation: 2,                                //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
        $Loop: 0,
        $AutoCenter: 0,
        $ArrowNavigatorOptions: {
          $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
          $ChanceToShow: 2                               //[Required] 0 Never, 1 Mouse Over, 2 Always
        }
      }
    };

    var jssor_slider1 = new $JssorSlider$("slider1_container", options);
    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizes
    function ScaleSlider() {
      var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
      if (parentWidth) {
        jssor_slider1.$ScaleWidth(Math.max(Math.min(parentWidth, 1170), 280), false);
      }
      else window.setTimeout(ScaleSlider, 30);
    }
    ScaleSlider();

    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    //responsive code end
    var $easyzoom = jQuery(".additional-images:last > div.easyzoom").easyZoom();

    $('.hasTooltip').tooltip();
    var click = true;
    $('a[data-fancybox="fancybox"]').fancybox({
      padding: 0,
      margin: 0,
      loop: true,
      openSpeed:500,
      closeSpeed:500,
      nextSpeed:500,
      prevSpeed:500,
      wrapCSS : 'photo-class',
      afterLoad : function (){
        <?php if($appWeb->client->mobile){ ?>
        $('body').swipe({
          swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
            click = false;
            if(direction == 'left'){
              $.fancybox.next();
            }
            if(direction == 'right'){
              $.fancybox.prev();
            }
            setTimeout(function(){
              click = true;
            },100)
          }
        });
        <?php } ?>
        $('.fancybox-inner').click(function(){
          if(click == true){
            $('body').toggleClass('fancybox-full');
          }
        })
      },
      beforeShow: function() {
        $('body').addClass('fancybox-lock');
      },
      afterClose : function() {
        $('body').removeClass('fancybox-lock');
        <?php if($appWeb->client->mobile){ ?>
        $('body').swipe('destroy');
        <?php } ?>
      },
      tpl : {
        image    : '<div class="fancybox-image" style="background-image: url(\'{href}\');"/>'
      },
      helpers: {
        title : null,
        thumbs: {
          height: 80,
          width: 80
        },
        overlay : {
          css : {
            'background' : '#191919'
          }
        }
      }
    });
  });
</script>


	<?php // Back To Category Button
  if ($this->product->virtuemart_category_id) {
    $catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
    $categoryName = vmText::_($this->product->category_name) ;
  } else {
    $catURL =  JRoute::_('index.php?option=com_virtuemart');
    $categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME') ;
  }
  ?>
  <div class="back-to-category">
      <a href="<?php echo $catURL ?>" class="btn product-details" title="<?php echo $categoryName ?>"><span><i class="fa fa-reply"></i>
<?php echo vmText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></span></a>
  </div>
    <?php
    // Product Edit Link
    //echo $this->edit_link;
    // Product Edit Link END
    ?>

    <?php
    // PDF - Print - Email Icon
    if (VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
	?>
        <div class="icons">
	    <?php

	    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;

		echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
	    //echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
		echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon',false,true,false,'class="printModal"');
	    ?>
    	<div class="clear"></div>
        </div>
    <?php } // PDF - Print END
    ?>

    <?php

	//echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
    ?>
<div class="row-fluid">
  <div class="vm-product-container span6">
    <div>
	    <div class="vm-product-media-container" id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 566px; height: 470px; overflow: hidden;">
<?php
echo $this->loadTemplate('images_additional');
?>
        <div u="thumbnavigator" class="jssort02" style="position: absolute; width: 86px; height: 470px; left:0px; bottom: 0px;">
          <div u="slides" style="cursor: move;">
            <div u="prototype" class="p" style="position: absolute; width: 86px; height: 86px; top: 0; left: 0;">
              <div class=w>
                <div u="thumbnailtemplate" style=" width: 100%; height: 100%; border: none;position:absolute; top: 0; left: 0;"></div>
              </div>
              <div class=c></div>
            </div>
          </div>
          <span u="arrowleft" class="jssora11l"></span>
          <span u="arrowright" class="jssora11r"></span>
        </div>
      </div>
    </div>
  </div>

    <?php // afterDisplayTitle Event
    echo $this->product->event->afterDisplayTitle ?>

	<div class="vm-product-details-container span6">

<div class="top_details">
  <div class="product_code"><?php echo vmText::_ ('TPL_PRODUCT_CODE').': <span>'.$this->product->product_sku; ?></span></div>
  <?php echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$this->product)); ?>
</div>
    <h3 class="heading-style-3"><?php echo $this->product->product_name ?></h3>
	    <div class="spacer-buy-area">

		<?php
		// TODO in Multi-Vendor not needed at the moment and just would lead to confusion
		/* $link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&virtuemart_vendor_id='.$this->product->virtuemart_vendor_id);
		  $text = vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
		  echo '<span class="bold">'. vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
		 */
		?>

		<?php

		/*if (is_array($this->productDisplayShipments)) {
		    foreach ($this->productDisplayShipments as $productDisplayShipment) {
			echo $productDisplayShipment . '<br />';
		    }
		}*/
		if (is_array($this->productDisplayPayments)) {
		    foreach ($this->productDisplayPayments as $productDisplayPayment) {
			echo $productDisplayPayment . '<br />';
		    }
		}

		//In case you are not happy using everywhere the same price display fromat, just create your own layout
		//in override /html/fields and use as first parameter the name of your file
		?>
		<div class="prices_block">
		<?php
		echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
		echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
		?>
		</div>
		<div class="divider divider_horizontal"></div>
		<?php
    echo shopFunctionsF::renderVmSubLayout('variants',array('product'=>$this->product));
		echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
		?>
    <?php  if (is_file(JPATH_BASE . "/components/com_tmbox/template/mywishlists.tpl2.php")) : ?>
          <div  class="wishlist list_wishlists<?php echo $this->product->virtuemart_product_id; ?>">
              <?php require(JPATH_BASE . "/components/com_tmbox/template/mywishlists.tpl2.php"); ?>
          </div>
      <?php endif; ?>
      <?php if (is_file(JPATH_BASE . "/components/com_tmbox/template/comparelist.tpl2.php")) : ?>
          <div  class="compare list_compare<?php echo $this->product->virtuemart_product_id; ?>">
              <?php require(JPATH_BASE . "/components/com_tmbox/template/comparelist.tpl2.php"); ?>
          </div>
      <?php endif; ?>
		<?php
		// Manufacturer of the Product
		if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
		    echo $this->loadTemplate('manufacturer');
		}

if (VmConfig::get('show_emailfriend')) {
	?>
        <div class="recommend_link">
	    <?php
		$MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id.'&tmpl=component';
	    echo '<a class="recommened-to-friend" href="'.$MailLink.'"><i class="fa fa-envelope"></i> '. vmText::_ ('TPL_RECOMMEND_TO_FRIEND') .'</a>'
	    ?>
        </div>
    <?php } // PDF - Print - Email Icon END
		?>

	    </div>

<?php

    // Ask a question about this product
    if (VmConfig::get('ask_question', 0) == 1) {
      $askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
      ?>
      <div class="ask-a-question">
        <a class="ask-a-question" href="<?php echo $askquestion_url ?>" rel="nofollow" ><i class="fa fa-question-circle"></i> <?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
      </div>
    <?php }

	// event onContentBeforeDisplay
	echo $this->product->event->beforeDisplayContent;
	// onContentAfterDisplay event
	echo $this->product->event->afterDisplayContent; ?>
	</div>

</div>
<div class="product_tabs">
<ul class="nav nav-tabs-vertical" id="bootstrap_tabs_nav_<?php echo $this->product->id; ?>">
<?php // Product Short Description
if (!empty($this->product->product_desc)) { ?>
	<li class="active"><a data-toggle="tab" href="#tab-0-<?php echo $this->product->id; ?>"><?php echo vmText::_ ('JDETAILS') ?></a></li>
	<?php
    } // Product Short Description END
     ?>
    <?php if (shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'additional')) || $this->product->product_length || $this->product->product_width || $this->product->product_height || $this->product->product_weight || $this->product->product_packaging || $this->product->product_box) { ?>
    <li><a data-toggle="tab" href="#tab-1-<?php echo $this->product->id; ?>"><?php echo vmText::_ ('TPL_ADDITIONAL_INFO'); ?></a></li>
    <?php } ?>
    <?php if ($this->showReview) { ?>
    <li><a data-toggle="tab" href="#tab-2-<?php echo $this->product->id; ?>"><?php echo vmText::_ ('COM_VIRTUEMART_REVIEWS') ?></a></li>
    <?php } ?>
    <?php if (shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'video'))) { ?>
    <li><a data-toggle="tab" href="#tab-3-<?php echo $this->product->id; ?>"><?php echo vmText::_ ('TPL_VIDEO'); ?></a></li>
    <?php } ?>
</ul>

<div class="tab-content" id="bootstrap_tabs_content_<?php echo $this->product->id; ?>">
<?php 
    if (!empty($this->product->product_desc)) {
	?>
	<div class="tab-pane fade active in" id="tab-0-<?php echo $this->product->id; ?>">
  <?php
	// Product Description
	if (!empty($this->product->product_desc)) {
	    ?>
        <div class="product-description">
	<?php /** @todo Test if content plugins modify the product description */ ?>
	<?php 
        echo $this->product->product_desc; 
        echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'normal'));
  ?>
        </div>
	<?php
    } // Product Description END
     ?>
	</div>
	<?php } ?>
    <?php if (shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'additional')) || $this->product->product_length || $this->product->product_width || $this->product->product_height || $this->product->product_weight || $this->product->product_packaging || $this->product->product_box) { ?>
    <div class="tab-pane fade" id="tab-1-<?php echo $this->product->id; ?>">
    	<?php echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'additional'));
      if($this->product->product_length) echo '<div class="product_dim">'.vmText::_('COM_VIRTUEMART_PRODUCT_LENGTH').': '.$this->product->product_length.' '.strtolower($this->product->product_lwh_uom).'</div>';
      if($this->product->product_width) echo '<div class="product_dim">'.vmText::_('COM_VIRTUEMART_PRODUCT_WIDTH').': '.$this->product->product_width.' '.strtolower($this->product->product_lwh_uom).'</div>';
      if($this->product->product_height) echo '<div class="product_dim">'.vmText::_('COM_VIRTUEMART_PRODUCT_HEIGHT').': '.$this->product->product_height.' '.strtolower($this->product->product_lwh_uom).'</div>';
      if($this->product->product_weight) echo '<div class="product_dim">'.vmText::_('COM_VIRTUEMART_PRODUCT_WEIGHT').': '.$this->product->product_weight. ' ' .strtolower($this->product->product_weight_uom).'</div>';
      if($this->product->product_packaging) echo '<div class="product_dim">'.vmText::_('COM_VIRTUEMART_PRODUCT_PACKAGING').': '.$this->product->product_packaging.' '.strtolower($this->product->product_unit).'</div>';
      if($this->product->product_box) echo '<div class="product_dim">'.vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX').$this->product->product_box.'</div>';
       ?>
    </div>
    <?php } ?>
    <?php if ($this->showReview) { ?>
    <div class="tab-pane fade" id="tab-2-<?php echo $this->product->id; ?>">
    	<?php echo $this->loadTemplate('reviews'); ?>
    </div>
    <?php } ?>
    <?php if (shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'video'))) { ?>
    <div class="tab-pane fade" id="tab-3-<?php echo $this->product->id; ?>">
    	<?php echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'video')); ?>
    </div>
    <?php } ?>
</div>
</div>
	<?php


	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'onbot'));

  echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products','customTitle' => true ));

	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_categories','class'=> 'product-related-categories'));
    
    // Product Navigation
    
    
// Show child categories
if (VmConfig::get('showCategory', 1)) {
	//echo $this->loadTemplate('showcategory');
}

$j = 'jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});
});';
//vmJsApi::addJScript('recalcReady',$j);

/** GALT
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
$j = "Virtuemart.container = jQuery('.productdetails-view');
Virtuemart.containerSelector = '.productdetails-view';";

vmJsApi::addJScript('ajaxContent',$j);

echo vmJsApi::writeJS();
?>
<script>
  jQuery(function($){
    addtocart = $('.addtocart-button[name="addtocart"]');
    validator = $('form.product.js-recalculate').validate({
      wrapper: 'mark',
      rules: {
        'quantity[]': {
          required: true,
          min: 1,
          digits: true
        }
      }
    })
  })  
</script>
<?php if (VmConfig::get('product_navigation', 1)) {
  ?>
        <div class="product-neighbours">
      <?php
      if (!empty($this->product->neighbours ['previous'][0])) {
    $prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
    echo JHtml::_('link', $prev_link, '<i class="fa fa-chevron-left"></i>'.JText::_('TPL_VIRTUEMART_PREVIOS_PRODUCTS'), array('rel'=>'prev', 'class' => 'previous-page','data-dynamic-update' => '1'));
      }
      if (!empty($this->product->neighbours ['next'][0])) {
    $next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
    echo JHtml::_('link', $next_link, JText::_('TPL_VIRTUEMART_NEXT_PRODUCTS').'<i class="fa fa-chevron-right"></i>
', array('rel'=>'next','class' => 'next-page','data-dynamic-update' => '1'));
      }
      ?>
      <div class="clear"></div>
        </div>
     <?php } ?>
</div>
</div>
<?php 
  //var_dump($this->product);
?>
<?php }else {
    echo "ap";
    $response = array("success" => true);
    // You can also return something like:
    $response = array("success" => false, "error"=> "Could not find ...");

    // Get the document object.
    $document = JFactory::getDocument();

    // Set the MIME type for JSON output.
    $document->setMimeEncoding('application/json');

    // Change the suggested filename.
    JResponse::setHeader('Content-Disposition','attachment;filename="result.json"');

    echo json_encode($response);
   //echo shopFunctionsF::renderVmSubLayout('variants',array('product'=>$this->product));
    //echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
    
}