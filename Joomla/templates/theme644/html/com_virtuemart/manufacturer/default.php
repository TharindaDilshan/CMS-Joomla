<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Manufacturer
* @author Kohl Patrick, Eugen Stranz
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 2701 2011-02-11 15:16:49Z impleri $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Category and Columns Counter
$iColumn = 1;
$iManufacturer = 1;

// Calculating Manufacturers Per Row
$manufacturerPerRow = VmConfig::get ('manufacturer_per_row', 3);
if ($manufacturerPerRow > 1) {
	$manufacturerCellWidth = ' span'.floor ( 12 / $manufacturerPerRow );
} else {
	$manufacturerCellWidth = '';
}
// Separator

// Lets output the categories, if there are some
if (!empty($this->manufacturers)) { ?>

<div class="manufacturer-view-default">
	<?php // Start the Output
	foreach ( $this->manufacturers as $manufacturer ) {

		// this is an indicator wether a row needs to be opened or not
		if ($iColumn == 1) { ?>
		<div class="row-fluid cols-<?php echo $manufacturerPerRow; ?>">
		<?php }

		// Manufacturer Elements
		$manufacturerURL = JRoute::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id, FALSE);
		$manufacturerIncludedProductsURL = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id, FALSE);
		$manufacturerImage = $manufacturer->images[0]->displayMediaThumb("",false);

		// Show Category ?>
		<div class="manufacturer item floatleft<?php echo $manufacturerCellWidth . $showVerticalSeparator ?>">
			<h5><a title="<?php echo $manufacturer->mf_name; ?>" href="<?php echo $manufacturerURL; ?>"><?php echo $manufacturer->mf_name; ?></a></h5>
			<a title="<?php echo $manufacturer->mf_name; ?>" href="<?php echo $manufacturerURL; ?>"><?php echo $manufacturerImage;?></a>
		</div>
		<?php
		$iManufacturer ++;

		// Do we need to close the current row now?
		if ($iColumn == $manufacturerPerRow) {
			echo '<div class="clear"></div></div>';
			$iColumn = 1;
		} else {
			$iColumn ++;
		}
	}

	// Do we need a final closing row tag?
	if ($iColumn != 1) { ?>
		<div class="clear"></div>
	</div>
	<?php } ?>

</div>
<?php
}
?>