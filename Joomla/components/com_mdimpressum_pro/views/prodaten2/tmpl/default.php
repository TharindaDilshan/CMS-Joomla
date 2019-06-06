<?php
/**
 * @version    CVS: 4.2.2
 * @package    Com_Mdimpressum_pro
 * @author     Axel Metz <office@megrodesign.com>
 * @copyright  Copyright (C) 2016. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>
<?php if ($this->item) : ?>

	<div class="item_fields">
		<table class="table">
			<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_UBERSCHRIFT2'); ?></th>
			<td><?php echo $this->item->uberschrift2; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_LINKEDIN'); ?></th>
			<td><?php echo $this->item->linkedin; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_PINTEREST'); ?></th>
			<td><?php echo $this->item->pinterest; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_PIWIK'); ?></th>
			<td><?php echo $this->item->piwik; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_TUMBLR'); ?></th>
			<td><?php echo $this->item->tumblr; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_TWITTER'); ?></th>
			<td><?php echo $this->item->twitter; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_XING'); ?></th>
			<td><?php echo $this->item->xing; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_AUSLOSPERR'); ?></th>
			<td><?php echo $this->item->auslosperr; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_SHOP'); ?></th>
			<td><?php echo $this->item->shop; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN2_DATENUBERMI'); ?></th>
			<td><?php echo $this->item->datenubermi; ?></td>
</tr>

		</table>
	</div>
	
	<?php
else:
	echo JText::_('COM_MDIMPRESSUM_PRO_ITEM_NOT_LOADED');
endif;
