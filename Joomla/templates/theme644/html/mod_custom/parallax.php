<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div <?php if ($params->get('backgroundimage')): ?> class="parallax" data-url="<?php echo JURI::base(true) . '/' . $params->get('backgroundimage');?>" data-mobile="true"<?php endif;?>>
	<?php echo $module->content;?>
</div>