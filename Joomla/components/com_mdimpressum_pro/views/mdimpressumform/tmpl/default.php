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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_mdimpressum_pro', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_mdimpressum_pro/js/form.js');

/**/
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-mdimpressum').submit(function (event) {
				
			});

			
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-mdimpressum').submit(function (event) {
				
			});

			
		});
	}
</script>

<div class="mdimpressum-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Edit <?php echo $this->item->id; ?></h1>
	<?php else: ?>
		<h1>Add</h1>
	<?php endif; ?>

	<form id="form-mdimpressum"
		  action="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&task=mdimpressum.save'); ?>"
		  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('firma'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('firma'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vrnummer'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vrnummer'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vorname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vorname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('str1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('str1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('plz1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('plz1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('ort1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('ort1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tel1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tel1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tel2'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tel2'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('land1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('land1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('email1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('email1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('email2'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('email2'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('fax1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('fax1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('mobil1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('mobil1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('website1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('website1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vnamen'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vnamen'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vnamen2'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vnamen2'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vsitz'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vsitz'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vearnr'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vearnr'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vlizbeh'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vlizbeh'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vfinanz'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vfinanz'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vstr'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vstr'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vplz'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vplz'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vort'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vort'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vemail'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vemail'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vtel'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vtel'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vberuf'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vberuf'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vkammer'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vkammer'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vgesetzlbest'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vgesetzlbest'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vland'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vland'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vaufsicht'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vaufsicht'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vhaft'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vhaft'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vversich'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vversich'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vversichnr'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vversichnr'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vpersons'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vpersons'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vsachs'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vsachs'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vregg'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vregg'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vreggnr'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vreggnr'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('ustid'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('ustid'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vwirtid'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vwirtid'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tvorname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tvorname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tstr'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tstr'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tplz'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tplz'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tort'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tort'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('temail'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('temail'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('ttel'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('ttel'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('inname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('inname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('invorname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('invorname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('instr'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('instr'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('inplz'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('inplz'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('inort'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('inort'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('inemail'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('inemail'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('intel'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('intel'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('bankinhaber'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('bankinhaber'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('bankname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('bankname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('bankiban'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('bankiban'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('bankswift'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('bankswift'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('templname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('templname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('templersteller'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('templersteller'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('templwebsite'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('templwebsite'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('linkdatenschutz'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('linkdatenschutz'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('copyfooter'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('copyfooter'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('werbungfooter'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('werbungfooter'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('frei1'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('frei1'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('frei2'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('frei2'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('frei3'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('frei3'); ?></div>
	</div>
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&task=mdimpressumform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_mdimpressum_pro"/>
		<input type="hidden" name="task"
			   value="mdimpressumform.save"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
