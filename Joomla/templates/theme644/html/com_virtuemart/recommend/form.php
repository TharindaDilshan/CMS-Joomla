<?php
/**
 *TODO Improve the CSS , ADD CATCHA ?
 * Show the form Ask a Question
 *
 * @package	VirtueMart
 * @subpackage
 * @author Kohl Patrick, Maik K�nnemann
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
* @version $Id: default.php 2810 2011-03-02 19:08:24Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$min = VmConfig::get('asks_minimum_comment_length', 50);
$max = VmConfig::get('asks_maximum_comment_length', 2000);

$vendorModel = VmModel::getModel ('vendor');
$this->vendor = $vendorModel->getVendor ($this->product->virtuemart_vendor_id);

$document = JFactory::getDocument();
$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);

JHtml::_('bootstrap.framework');
$document->addScript('templates/'.$template->template.'/js/jquery.validate.min.js');
$document->addScript('templates/'.$template->template.'/js/additional-methods.min.js');

/* Let's see if we found the product */
if (empty ( $this->product )) {
	echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_NOT_FOUND' );
	echo '<br /><br />  ' . $this->continue_link_html;
} else {
	$session = JFactory::getSession();
	$mailRecommendData = $session->get('mailrecommend', 0, 'vm');
	if(!empty($this->login)){
		echo $this->login;
	}
	if(empty($this->login) or VmConfig::get('recommend_unauth',false)){
		?>
		<div id="com_virtuemart">
		<div class="ask-a-question-view">
			<h4 class="heading-style-4"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_RECOMMEND')  ?></h4>

			<div class="form-field">

				<form method="post" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$this->product->virtuemart_product_id.'&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE).'&tmpl=component' ; ?>" name="askform" id="askform" >

					<div class="control-group">
						<div class="control-label"><label for="name"><?php echo vmText::_('COM_VIRTUEMART_RECOMMEND_NAME')  ?> : </label></div>
						<div class="controls"><input type="text" value="<?php echo $mailRecommendData['name']; ?>" name="name" id="name" size="30" required></div>
						</div>
						<div class="control-group">
						<div class="control-label"><label for="email"><?php echo vmText::_('COM_VIRTUEMART_RECOMMEND_EMAIL')  ?> : </label></div>
						<div class="controls"><input type="text" value="<?php echo $mailRecommendData['email'] ?>" name="email" id="email" size="30" required></div>
						</div>
						<div class="control-group">
							<div class="control-label">
							<label for="comment"><?php echo vmText::sprintf('COM_VIRTUEMART_COMMENT', $min, $max); ?></label>
							</div>
							<div class="controls">
							<textarea id="comment" name="comment" rows="8" required><?php echo $mailRecommendData['comment']; ?></textarea></div>
						</div>

						<div class="submit controls">
							<?php // captcha addition
							if(VmConfig::get ('ask_captcha')){
								JHTML::_('behavior.framework');
								$enabled = JPluginHelper::isEnabled('captcha');
								if($enabled){
									JPluginHelper::importPlugin('captcha');
									$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
								}
								?>
								<div id="dynamic_recaptcha_1"></div>
							<?php
							}
							// end of captcha addition
							?>
            			<div>
  						<div class="floatleft width50 ">
                <button class="btn" type="submit" name="submit_ask" title="<?php echo vmText::_('COM_VIRTUEMART_RECOMMEND_SUBMIT')  ?>"><span><?php echo vmText::_('COM_VIRTUEMART_RECOMMEND_SUBMIT'); ?></span></button>
              </div>
            </div>
          </div>

					<input type="hidden" name="virtuemart_product_id" value="<?php echo vRequest::getInt('virtuemart_product_id',0); ?>" />
					<input type="hidden" name="tmpl" value="component" />
					<input type="hidden" name="view" value="productdetails" />
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="virtuemart_category_id" value="<?php echo vRequest::getInt('virtuemart_category_id'); ?>" />
					<input type="hidden" name="task" value="mailRecommend" />
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>

			</div>
		</div>
		<script>
		  jQuery(document).bind('ready', function(){
		    validator = jQuery('#askform').validate({
		    	wrapper: 'mark',
		    	rules: {
		    		comment: {
		    			minlength: <?php echo $min ?>,
		    			maxlength: <?php echo $max ?>
		    		}
		    	}
		    })
		  })  
		</script>
		
	</div>
<?php }
}?>

