<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

include ('includes/includes.php');

require_once JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';

$twofactormethods = UsersHelper::getTwoFactorMethods();

if($this->params->get('countdown_time')){
  $timestamp = JHtml::_('date', $this->params->get('countdown_time'), 'U') - date('Z') - date('U');
}

?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <jdoc:include type="head" />
    <?php
      $doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
      if($timestamp>0){
        $doc->addStyleSheet('templates/'.$this->template.'/css/flipclock.css');
        $doc->addScript('templates/'.$this->template.'/js/flipclock.min.js');
      }
      $doc->addScript('templates/'.$this->template.'/js/jquery.validate.min.js');
      $doc->addScript('templates/'.$this->template.'/js/additional-methods.min.js');
    ?>
  </head>
  <body class="com_config">
    <!--[if lt IE 9]>
      <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
          <img src="templates/<?php echo $this->template; ?>/images/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
      </div>
    <![endif]-->
    <div class="offline_container">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="well">
              <?php if ($app->getCfg('offline_image')) : ?>
              <img src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->getCfg('sitename')); ?>" />
              <?php endif; ?>
              <div id="logo">
                <a href="<?php echo JURI::base(); ?>">
                  <?php if(isset($logo)) : ?>
                  <img src="<?php echo $logo;?>" alt="<?php echo $sitename; ?>">
                  <h1><?php echo $sitename; ?></h1>
                  <?php else : ?><h1><?php echo wrap_chars_with_span($sitename); ?></h1><?php endif; ?>
                </a>
              </div>
              <?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != ''): ?>
              <p class="offline_message"><?php echo $app->getCfg('offline_message'); ?></p>
              <?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != ''): ?>
              <p><?php echo JText::_('JOFFLINE_MESSAGE'); ?></p>
              <?php  endif; 
              if($timestamp>0) : ?>
              <div class="countdown"></div>
              <?php endif; ?>
              <jdoc:include type="message" />
              <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" novalidate>
                <fieldset class="input">
                  <div id="form-login-username">
                    <input name="username" id="username" type="text" class="inputbox" size="18" placeholder="<?php echo JText::_('JGLOBAL_USERNAME') ?>" required>
                  </div>
                  <div id="form-login-password">
                    <input type="password" name="password" class="inputbox" size="18" id="passwd" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" required>
                  </div>
                  <?php if (count($twofactormethods) > 1) : ?>
                  <div id="form-login-secretkey">
                    <input type="text" name="secretkey" class="inputbox" autocomplete="off" size="18" id="secretkey" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>">
                  </div>
                  <?php endif; ?>
                  <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                  <div id="form-login-remember">
                    <span class="checkbox">
                    <input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" id="remember" />
                    <label class="checkbox_inner" for="remember"></label>
                    </span>
                    <label class="checkbox" for="remember">
                      <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>
                    </label>
                  </div>
                  <?php endif; ?>
                  <button type="submit" name="Submit" class="btn button" value="<?php echo JText::_('JLOGIN') ?>"><span><?php echo JText::_('JLOGIN') ?></span></button>
                  <input type="hidden" name="option" value="com_users">
                  <input type="hidden" name="task" value="user.login">
                  <input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>">
                  <?php echo JHtml::_('form.token'); ?>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <jdoc:include type="modules" name="fixed-sidebar-left" style="none" />
    <?php 
      $doc->addScript('templates/'.$this->template.'/js/offline.js');
    ?>
    <script>
      jQuery(function($){
        $('#form-login').validate({
          wrapper: "mark"
        });
        <?php if($timestamp>0){ ?>
          var clock = $('.countdown').FlipClock(<?php echo $timestamp; ?>, {
            countdown: true,
            clockFace: 'DailyCounter'
          });
        <?php } ?>
      })
    </script>
  </body>
</html>