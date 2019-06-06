<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 7784 2014-03-25 00:18:44Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<div class="additional-images" u="slides" style="left: 96px; top: 0px; width: 468px; height: 468px; border: 1px solid;">
	<?php
	$start_image = VmConfig::get('add_img_main', 1) ? 0 : 1;
	for ($i = $start_image; $i < count($this->product->images); $i++) {
		$image = $this->product->images[$i];
		?>
		<div class="easyzoom easyzoom--overlay">
			<?php
				echo '<a class="fancybox-thumb" title="'.vmText::_('TPL_CLICK_TO_ENLARGE').'" data-fancybox-type="image" data-fancybox-group="product" data-fancybox="fancybox" href="'.JURI::base(true).'/'.$image->file_url.'">'.$image->displayMediaFull('u="image"',false,'').'</a>';
				echo $image->displayMediaThumb('u="thumb"',false,'');
			?>
		</div>
	<?php
	}
	?>
</div>

