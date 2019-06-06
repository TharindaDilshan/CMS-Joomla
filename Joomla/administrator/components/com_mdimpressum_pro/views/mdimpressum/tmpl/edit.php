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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_mdimpressum_pro/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'mdimpressum.cancel') {
			Joomla.submitform(task, document.getElementById('mdimpressum-form'));
		}
		else {
			
			if (task != 'mdimpressum.cancel' && document.formvalidator.isValid(document.id('mdimpressum-form'))) {
				
				Joomla.submitform(task, document.getElementById('mdimpressum-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="mdimpressum-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('Impressum', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
                                        
                                        
                                        
                                        
                                        
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
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tel2'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('land1'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('email1'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('email2'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('fax1'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('mobil1'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('website1'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
                                </div></div></div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'misc', JText::_('Vertr. berechtigte Personen', true)); ?>                   
                 <div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
			       
                                        
                                        
                                        
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vnamen'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('vnamen'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vnamen2'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vsitz'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vearnr'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vlizbeh'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vfinanz'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vstr'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vplz'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vort'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vemail'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vtel'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vberuf'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vkammer'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vgesetzlbest'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vland'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vaufsicht'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vhaft'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vversich'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vversichnr'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vpersons'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vsachs'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vregg'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vreggnr'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('ustid'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vwirtid'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
                                </div></div></div>                           
                         <?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tv', JText::_('techn. verantwortliche Person', true)); ?>                   
                 <div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">                   

                                            
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
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tplz'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tort'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('temail'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('ttel'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
                            </div></div></div>         
                      <?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'iv', JText::_('inhaltl. verantwortliche Person', true)); ?>                   
                 <div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">               
                                    
                                    
                                    
                                    
                                    
                                    
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
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('inplz'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('inort'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('inemail'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('intel'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
                     </div></div></div>         
                      <?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'ba', JText::_('Bankdaten', true)); ?>                   
                 <div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">                
                                    
                                    
                                    
                                    
                                    
                                    
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
                 </div></div></div>         
                      <?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'te', JText::_('Templatedaten', true)); ?>                   
                 <div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">                   
                                    
                                    
                                    
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
                                    
                                    
                          </div></div></div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'link', JText::_('Datenschutz Link', true)); ?>                   
                 <div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">          
                                    
                                    
                                    
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('linkdatenschutz'); ?></div>
				<div class="controls"><?php echo $this->escape(JText::_('ONLY_IN_PRO_VERSION')); ?></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('werbungfooter'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('werbungfooter'); ?></div>
			</div>
                     
                                </div></div>  </div>   
                        <?php echo JHtml::_('bootstrap.endTab'); ?>

		               
                                        
                 <!--                       
			<div class="control-group">
				<div class="control-label"><?php //echo $this->form->getLabel('frei1'); ?></div>
				<div class="controls"><?php //echo $this->form->getInput('frei1'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php //echo $this->form->getLabel('frei2'); ?></div>
				<div class="controls"><?php //echo $this->form->getInput('frei2'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php //echo $this->form->getLabel('frei3'); ?></div>
				<div class="controls"><?php //echo $this->form->getInput('frei3'); ?></div>
			</div>
-->

					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		
		

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	
</form>
