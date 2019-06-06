<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$task = $app->input->getCmd('task', '');

$getColorScheme = $this->params->get('color_scheme');
include_once ('includes/includes.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
  <head>
    <link rel="stylesheet" href="templates/<?php echo $this->template; ?>/css/template.css" />
    <?php if($getColorScheme){ ?>
    <link rel="stylesheet" href="templates/<?php echo $this->template; ?>/css/color_schemes/<?php echo $getColorScheme ?>.css" />
    <?php } ?>
    <jdoc:include type="head" />
  </head>
  <body class="contentpane modal">
    <jdoc:include type="message" />
    <jdoc:include type="component" />
    <script src="templates/<?php echo $this->template; ?>/js/easing.js"></script>
  </body>
</html>
