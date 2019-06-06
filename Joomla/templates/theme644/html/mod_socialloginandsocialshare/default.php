<?php 
/*------------------------------------------------------------------------
# mod_SocialLoginandSocialShare
# ------------------------------------------------------------------------
# author    LoginRadius inc.
# copyright Copyright (C) 2013 loginradius.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.loginradius.com
# Technical Support:  Forum - http://community.loginradius.com/
-------------------------------------------------------------------------*/

//no direct access
defined( '_JEXEC' ) or die('Restricted access');
JHtml::_('behavior.keepalive');
$moduleParams = json_decode($module->params);
$moduleclass_sfx = $moduleParams->moduleclass_sfx;
JFactory::getLanguage()->load('mod_login');
jimport( 'joomla.filter.filteroutput' );
?>
<i class="fa fa-bars toogle" aria-hidden="true"></i>

<?php 
// Check for plugin enabled.
  jimport('joomla.plugin.helper');
  if(!JPluginHelper::isEnabled('system','socialloginandsocialshare')) :
    JError::raiseNotice ('sociallogin_plugin', JText::_ ('MOD_LOGINRADIUS_PLUGIN_ERROR')); 
   endif; ?>

<?php
if ($type == 'logout') : ?>
<?php 
$session = JFactory::getSession();
$doc = JFactory::getDocument();

$app    = JFactory::getApplication();
$loginmenu = JMenu::getInstance('site');

$navlogin = $app->getMenu();
$navlogin = $loginmenu->getActive();



$Link1 = $loginmenu->getItem($params->get('itemlink'));
$item1_link_url = JRoute::_($Link1->link.'&Itemid='.$Link1->id);

$Link2 = $loginmenu->getItem($params->get('itemlink2'));
$item2_link_url = JRoute::_($Link2->link.'&Itemid='.$Link2->id);

$Link3 = $loginmenu->getItem($params->get('itemlink3'));
$item3_link_url = JRoute::_($Link3->link.'&Itemid='.$Link3->id);

$Link4 = $loginmenu->getItem($params->get('itemlink4'));
$item4_link_url = JRoute::_($Link4->link.'&Itemid='.$Link4->id);

$Link5 = $loginmenu->getItem($params->get('itemlink5'));
$item5_link_url = JRoute::_($Link5->link.'&Itemid='.$Link5->id);

$template = $app->getTemplate();
$navlogin = $loginmenu->getActive();
?>
  <form action="<?php echo vmURI::getCleanUrl(), $params->get('usesecure'); ?>" method="post" id="login-form__<?php echo $module->id; ?>" class="login-form">
      <?php 
        //$name = $user->get('name');
       // if(!empty($name)):
                 // echo JHtml::_('link', JRoute::_('index.php?option=com_users&view=profile'), $name);
                //else :
                  //echo JHtml::_('link', JRoute::_('index.php?option=com_users&view=profile'), $user->get('username'));
                ///endif;
        //?>
	   <div class="logout-button">
    <div class="titlelogin">
      <button type="submit" name="Submit" class="link" value="<?php echo JText::_('JLOGOUT'); ?>"><?php echo JText::_('JLOGOUT'); ?></button>
      <?php 
      if($params->get('itembutton1')){ ?>
        <a class="link <?php  
         if ($navlogin->id == $Link1->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item1_link_url; ?>"><?php echo $params->get('item_titles'); ?></a>
      <?php } 
      if($params->get('itembutton2')){ ?>
        <a class="link <?php if ($navlogin->id == $Link2->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item2_link_url; ?>"><?php echo $params->get('item_titles2'); ?></a>
      <?php }

      if($params->get('itembutton3')){ ?>
        <a class="link <?php  if ($navlogin->id == $Link3->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item3_link_url; ?>"><?php echo $params->get('item_titles3'); ?></a>
      <?php }

      if($params->get('itembutton4')){ ?>
        <a class="link <?php  if ($navlogin->id == $Link4->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item4_link_url; ?>"><?php echo $params->get('item_titles4'); ?></a>
      <?php }

      if($params->get('itembutton5')){ ?>
        <a class="link <?php if ($navlogin->id == $Link5->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item5_link_url; ?>"><?php echo $params->get('item_titles5'); ?></a>
      <?php }

      ?>
  </div> 
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token');?>		
	  </div>
  <div style="clear:both"></div>
   </form>
<?php else :
$doc = JFactory::getDocument();

$app    = JFactory::getApplication();
$loginmenu = JMenu::getInstance('site');

$navlogin = $app->getMenu();
$navlogin = $loginmenu->getActive();



$Link1 = $loginmenu->getItem($params->get('itemlink'));
$item1_link_url = JRoute::_($Link1->link.'&Itemid='.$Link1->id);

$Link2 = $loginmenu->getItem($params->get('itemlink2'));
$item2_link_url = JRoute::_($Link2->link.'&Itemid='.$Link2->id);

$Link3 = $loginmenu->getItem($params->get('itemlink3'));
$item3_link_url = JRoute::_($Link3->link.'&Itemid='.$Link3->id);

$Link4 = $loginmenu->getItem($params->get('itemlink4'));
$item4_link_url = JRoute::_($Link4->link.'&Itemid='.$Link4->id);

$Link5 = $loginmenu->getItem($params->get('itemlink5'));
$item5_link_url = JRoute::_($Link5->link.'&Itemid='.$Link5->id);

$template = $app->getTemplate();
$navlogin = $loginmenu->getActive();
$doc->addScript('templates/'.$template.'/js/jquery.validate.min.js');
$doc->addScript('templates/'.$template.'/js/additional-methods.min.js'); ?>

<div class="titlelogin"><a class="link" href="#myModals" role="button" data-toggle="modal"><?php echo JText::_('JLOGIN') ?></a>
<?php 
    if($params->get('itembutton1')){ ?>
      <a class="link <?php  
       if ($navlogin->id == $Link1->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item1_link_url; ?>"><?php echo $params->get('item_titles'); ?></a>
    <?php } 
    if($params->get('itembutton2')){ ?>
      <a class="link <?php if ($navlogin->id == $Link2->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item2_link_url; ?>"><?php echo $params->get('item_titles2'); ?></a>
    <?php }

    if($params->get('itembutton3')){ ?>
      <a class="link <?php  if ($navlogin->id == $Link3->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item3_link_url; ?>"><?php echo $params->get('item_titles3'); ?></a>
    <?php }

    if($params->get('itembutton4')){ ?>
      <a class="link <?php  if ($navlogin->id == $Link4->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item4_link_url; ?>"><?php echo $params->get('item_titles4'); ?></a>
    <?php }

    if($params->get('itembutton5')){ ?>
      <a class="link <?php if ($navlogin->id == $Link5->id) { echo 'active';}else{echo 'notactive';}?>" href="<?php echo $item5_link_url; ?>"><?php echo $params->get('item_titles5'); ?></a>
    <?php }

    ?>
</div>                  
<div id="myModals" class="modal fade hide" tabindex="-1" role="dialog" aria-labelledby="myModalsLabel" aria-hidden="true">
  <div class="modal-backdrop in" aria-hidden="true" data-dismiss="modal"></div>
  <div class="modal-dialog" role="document">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><?php echo JText::_('TPL_LOGIN_FORM') ?></h3>
  </div>
  <form action="<?php echo vmURI::getCleanUrl(), $params->get('usesecure'); ?>" method="post" class="login-form" id="login-form__<?php echo $module->id; ?>">
    <?php if ($params->get('pretext')): ?>
    <div class="mod-login_pretext">
      <p><?php echo $params->get('pretext'); ?></p>
    </div>
    <?php endif; ?>
    <div class="mod-login_userdata">
    <div id="form-login-username" class="control-group">
      <div class="controls">
        <?php if (!$params->get('usetext')) : ?>
          <div class="input-prepend">
            <span class="add-on">
              <span class="fa fa-user hasTooltip" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"></span>
              <label for="modlgn-username" class="element-invisible"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
            </span>
            <input id="modlgn-username" type="text" name="username" class="input-box" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" required>
          </div>
        <?php else: ?>
        <input id="mod-login_username<?php echo $module->id; ?>" class="inputbox mod-login_username" type="text" name="username" tabindex="1" size="18" placeholder="<?php echo JText::_('TPL_MOD_LOGIN_VALUE_USERNAME') ?>" required>
        <?php endif; ?>
      </div>
    </div>
    <div id="form-login-password" class="control-group">
      <div class="controls">
        <?php if (!$params->get('usetext')) : ?>
          <div class="input-prepend">
            <span class="add-on">
              <span class="fa fa-lock hasTooltip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>">
              </span>
                <label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?>
              </label>
            </span>
            <input id="modlgn-passwd" type="password" name="password" class="input-box" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" required>
          </div>
        <?php else: ?>
        <input id="mod-login_passwd<?php echo $module->id; ?>" class="inputbox mod-login_passwd" type="password" name="password" tabindex="2" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"  required>
        <?php endif; ?>
      </div>
    </div>    
    <?php if (count($twofactormethods) > 1): ?>
    <div id="form-login-secretkey" class="control-group">
      <div class="controls">
        <?php if (!$params->get('usetext')) : ?>
          <div class="input-prepend input-append">
            <span class="add-on">
              <span class="fa fa-star hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>">
              </span>
                <label for="modlgn-secretkey" class="element-invisible"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?>
              </label>
            </span>
            <input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
            <span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
              <span class="fa fa-question-circle"></span>
            </span>
        </div>
        <?php else: ?>
          <label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY') ?></label>
          <input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
          <span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
            <span class="icon-help"></span>
          </span>
        <?php endif; ?>

      </div>
    </div>
    <?php endif; ?>
      <div class="mod-login_submit">
        <button type="submit" tabindex="3" name="Submit" class="btn btn-primary"><?php echo JText::_('JLOGIN') ?></button>
        <?php $usersConfig = JComponentHelper::getParams('com_users');
        if ($usersConfig->get('allowUserRegistration')) : ?>
        <a class="btn btn-primary register" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
        <?php endif; ?>
      </div>
      <input type="hidden" name="option" value="com_users">
      <input type="hidden" name="task" value="user.login">
      <input type="hidden" name="return" value="<?php echo $return; ?>">
      <?php echo JHtml::_('form.token'); ?>
      <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
      <label for="mod-login_remember<?php echo $module->id; ?>" class="checkbox">
        <input id="mod-login_remember<?php echo $module->id; ?>" class="mod-login_remember" type="checkbox" name="remember" value="yes">
        <?php echo JText::_('TPL_MOD_LOGIN_REMEMBER_ME') ?>
      </label> 
      <?php endif; ?>
      <div class="reset_remind">
      <?php echo JText::_('TPL_FORGOT'); ?>
      <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" class="hasTooltip"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>/
      <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="hasTooltip"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>?
      </div>
    </div>
    <?php if ($params->get('posttext')): ?>
    <div class="mod-login_posttext">
      <p><?php echo $params->get('posttext'); ?></p>
    </div>
    <?php endif; ?>
  </form>
<script>
  jQuery(document).bind('ready', function(){
    jQuery( ".titlelogin a" ).on( "click", function() {
        jQuery('.olrk-noquirks').addClass('z-index');
      });
    jQuery('#myModals').modal({
      backdrop:false,
      show:false
    });
     jQuery( "#myModals .close , #myModals .modal-backdrop" ).on( "click", function() {
        jQuery('.olrk-noquirks').removeClass('z-index');
      });
      validator = jQuery('#login-form__<?php echo $module->id; ?>').validate({
        wrapper: 'mark'
      })
  })
</script>
<?php echo $sociallogin; ?>
</div></div>
<?php
endif; ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.moduletable.login  .toogle').click(function(){
      jQuery('.moduletable.login').toggleClass('open')
    });
      jQuery(document).on('click touchmove',function(e) {
          var container = jQuery(".moduletable.login");
          if (!container.is(e.target)
              && container.has(e.target).length === 0 && container.hasClass('open'))
          {
              jQuery('.moduletable.login').toggleClass('open')
          }
      })

  })
</script>