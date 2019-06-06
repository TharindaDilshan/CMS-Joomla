<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col= 1 ;
?>
<div class="vmgroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">

<?php if ($headerText) : ?>
	<div class="vmheader"><?php echo $headerText ?></div>
<?php endif;
if ($display_style =="div") { ?>
	<div class="vmmanufacturer vmmanufacturer_<?php echo $params->get('moduleclass_sfx'); ?> cols_<?php echo $manufacturers_per_row; ?> row-fluid">
	<?php foreach ($manufacturers as $manufacturer) {
		$link = JROUTE::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id);

		?>
		<div class="span<?php echo round(12/$manufacturers_per_row) ?>">
		<?php
		if ($manufacturer->images && ($show == 'image' or $show == 'all' )) { ?>
			<a href="<?php echo $link; ?>"><?php echo $manufacturer->images[0]->displayMediaThumb('',false);?></a>
		<?php
		}
		if ($show == 'text' or $show == 'all' ) { ?>
		 <h6 class="manufacturer_name"><a href="<?php echo $link; ?>"><?php echo $manufacturer->mf_name; ?></a></h6>
		<?php
		} ?>
		</div>
		<?php
		if ($col == $manufacturers_per_row) {
			echo '</div><div class="vmmanufacturer vmmanufacturer_'. $params->get("moduleclass_sfx").' cols_'. $manufacturers_per_row.' row-fluid">';
			$col= 1 ;
		} else {
			$col++;
		}
	} ?>
	</div>

<?php
} else {
	$last = count($manufacturers)-1;
?>

<ul class="vmmanufacturer<?php echo $params->get('moduleclass_sfx'); ?>">
<?php
foreach ($manufacturers as $manufacturer) {
	$link = JROUTE::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id);
	?>
	<li><a href="<?php echo $link; ?>">
		<?php
		if ($manufacturer->images && ($show == 'image' or $show == 'all' )) { ?>
			<?php echo $manufacturer->images[0]->displayMediaFull('',false);?>
		<?php
		}
		if ($show == 'text' or $show == 'all' ) { ?>
		 <?php echo $manufacturer->mf_name; ?>
		<?php
		}
		?>
		</a>
	</li>
	<?php
	if ($col == $manufacturers_per_row && $manufacturers_per_row && $last) {
		echo '</ul><ul class="vmmanufacturer'.$params->get('moduleclass_sfx').'">';
		$col= 1 ;
	} else {
		$col++;
	}
	$last--;
} ?>
<div class="clearfix"></div>
</ul>

<?php }
	if ($footerText) : ?>
	<div class="vmfooter<?php echo $params->get( 'moduleclass_sfx' ) ?>">
		 <?php echo $footerText ?>
	</div>
<?php endif; ?>
</div>

