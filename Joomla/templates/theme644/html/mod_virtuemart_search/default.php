<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<!--BEGIN Search Box -->
<i class="fa fa-search toogle" aria-hidden="true"></i>

<form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id ); ?>" method="get" class="mod_virtuemart_search">
<div class="search<?php echo $params->get('moduleclass_sfx'); ?>">
<?php $output = '<input name="keyword" id="mod_virtuemart_search" maxlength="'.$maxlength.'" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" placeholder="'.$text.'" required>';

			if ($button) :
			    if ($imagebutton) :
			        $button = '<button type="submit" value="'.$button_text.'" class="btn button'.$moduleclass_sfx.'" onclick="this.form.keyword.focus();"><i class="fa fa-search"></i></button>';
			    else :
			        $button = '<button type="submit" value="'.$button_text.'" class="btn button'.$moduleclass_sfx.'" onclick="this.form.keyword.focus();">'.$button_text.'</button>';
			    endif;
		

			switch ($button_pos) :
			    case 'top' :
				    $button = $button.'<br />';
				    $output = $button.$output;
				    break;

			    case 'bottom' :
				    $button = '<br />'.$button;
				    $output = $output.$button;
				    break;

			    case 'right' :
				    $output = $output.$button;
				    break;

			    case 'left' :
			    default :
				    $output = $button.$output;
				    break;
			endswitch;
			endif;
			
			echo $output;
?>
</label>
</div>
		<input type="hidden" name="limitstart" value="0" />
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="view" value="category" />
		<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>
<?php if(!empty($set_Itemid)){
	echo '<input type="hidden" name="Itemid" value="'.$set_Itemid.'" />';
} ?>

	  </form>

<!-- End Search Box -->
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.moduletable.virtuemart_search   .toogle').click(function(){
      jQuery('.moduletable.virtuemart_search ').toggleClass('open')
    });
      jQuery(document).on('click touchmove',function(e) {
          var container = jQuery(".moduletable.virtuemart_search ");
          if (!container.is(e.target)
              && container.has(e.target).length === 0 && container.hasClass('open'))
          {
              jQuery('.moduletable.virtuemart_search ').toggleClass('open')
          }
      })

  })
</script>