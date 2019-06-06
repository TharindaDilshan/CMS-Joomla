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
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_UBERSCHRIFT3'); ?></th>
			<td><?php echo $this->item->uberschrift3; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_COOKIES'); ?></th>
			<td><?php echo $this->item->cookies; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_SERVERLOG'); ?></th>
			<td><?php echo $this->item->serverlog; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_KONTFORM'); ?></th>
			<td><?php echo $this->item->kontform; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_WERBEMAILS'); ?></th>
			<td><?php echo $this->item->werbemails; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_NEWSLETTERDATEN'); ?></th>
			<td><?php echo $this->item->newsletterdaten; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_REG'); ?></th>
			<td><?php echo $this->item->reg; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_VERARBDATEN'); ?></th>
			<td><?php echo $this->item->verarbdaten; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_ZUSATZ1'); ?></th>
			<td><?php echo $this->item->zusatz1; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_PRODATEN3_ZUSATZ2'); ?></th>
			<td><?php echo $this->item->zusatz2; ?></td>
</tr>

		</table>
	</div>
	
	<?php
else:
	echo JText::_('COM_MDIMPRESSUM_PRO_ITEM_NOT_LOADED');
endif;
