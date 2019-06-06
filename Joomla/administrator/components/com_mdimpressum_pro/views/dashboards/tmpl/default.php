<?php
/**
 * @version    CVS: 4.2.2
 * @package    Com_Mdimpressum_pro
 * @author     Axel Metz <office@megrodesign.com>
 * @copyright  Copyright (C) 2016. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder sp&Atilde;&curren;ter; siehe LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_mdimpressum_pro/assets/css/mdimpressum_pro.css');
$document->addStyleSheet(JUri::root() . 'media/com_mdimpressum_pro/css/list.css');
$document->addStyleSheet(JUri::root() . 'administrator/components/com_mdimpressum_pro/assets/css/dashboard.css');
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_mdimpressum_pro');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_mdimpressum_pro&task=dashboards.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'List', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});
</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&view=dashboards'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

<div class="span12"><strong>
<span style="font-size:15px; text-align:center;" >Unsere Komponenten auf einen Blick! </span>
</div>

<div class="span11">

		<div id="dashboard-left">
			<div class="dashboard-container">
						<div class="dashboard-info dashboard-button">
				<a href="http://www.megrodesign.de/demo/index.php/contacts-pro" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="MD Contacts" />
                    <span class="dashboard-title">CONTACTS PRO</span>
					 </a>
				 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://megrodesign.com" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Therms of Service" />
					<span class="dashboard-title">THERMS OF SERVICE</span></a> 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://megrodesign.com" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Total Users" />
					<span class="dashboard-title">TOTAL USERS</span> 
				</a> 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://megrodesign.com/" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Pricelist" />
					<span class="dashboard-title">PREISLIST</span> 
				</a> 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://www.megrodesign.de/demo/index.php/disclaimer-pro" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Disclaimer" />
					<span class="dashboard-title">DISCLAIMER</span> 
				</a> 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://www.megrodesign.de/demo/index.php/restaurant-pro" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Adressbook" />
					<span class="dashboard-title">RESTAURANT PRO</span>
				</a> 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://www.megrodesign.de/demo/index.php/impressum-pro" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Impressum PRO" />
					<span class="dashboard-title">IMPRESSUM PRO</span> 
				</a> 
			</div>
								<div class="dashboard-info dashboard-button">
				<a href="http://megrodesign.com/index.php/de/forum2" target="_blank">
					<img src="http://www.megrodesign.com/images/componentenbilder/pic-comp.png" alt="Support" />
					<span class="dashboard-title">SUPPORT</span> 
				</a> 
			</div>
			</div>
<span class="rsform_clear_both"></span>		</div>
		<div id="dashboard-right" class="hidden-phone hidden-tablet">
			<div class="dashboard-container">
	<div class="dashboard-info">
		<img src="http://www.megrodesign.com/images/componentenbilder/logo-right.png" align="middle" alt="MegroDesign" />
		<table class="dashboard-table">
			<tr>
				<td nowrap="nowrap"><strong>Version: </strong></td>
				<td nowrap="nowrap">MD Impressum Light</td>
			</tr>
			<tr>
				<td nowrap="nowrap"><strong>Copyright: </strong></td>
				<td nowrap="nowrap"><?php echo ' Copyright &copy; '.date("Y");?>  by <a href="http://megrodesign.com" >Megrodesign.com</a></td>
			</tr>
			<tr>
				<td nowrap="nowrap"><strong>Lizenz: </strong></td>
				<td nowrap="nowrap"><a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU/GPL</a></td>
			</tr>
			<tr>
				<td nowrap="nowrap"><strong>Update: </strong></td>
								<td nowrap="nowrap" class="missing-code"><a href="index.php?option=com_installer&view=update">Hier finden Sie alle Updates!</a>
				
				</td>
							</tr>
		</table>
	</div>
</div>
</div>





<div class="clearfix"></div>
<div class="span7">
    <strong><div id="just">Diese Light Version dient nur zum Verst&auml;ndnis und zur Arbeitsweise unserer Pro Version. In der Light Version sind nur Beispieldaten verf&uuml;gbar. M&ouml;chten Sie alle Felder nutzen, ben&ouml;tigen Sie eine Pro Version. Mitgliedschaft -BASIC- .Wir &uuml;bernehmen keinerlei Haftung f&uuml;r die Richtigkeit, Aktualit&auml;t und Vollst&auml;ndigkeit der Komponenten und deren Inhalte! Diese Komponente und deren Inhalte kann keine Rechtsberatung ersetzen. Bitte wenden Sie sich dazu an einen spezialisierten Rechtsanwalt.
Viele datenschutzrechtliche Fragen k&ouml;nnen nur im Einzelfall und bei einer individuellen anwaltlichen Pr&uuml;fung korrekt umgesetzt werden.
        </div></strong>

    <div style="margin-top:50px;"><p style="font-size:15px; text-align:center;" >MD IMPRESSUM LIGHT <span class="small"></span>
	<?php echo ' Copyright &copy; '.date("Y");?>  by <a href="http://megrodesign.com" >Megrodesign.com</a></p></div>




		
		<?php echo JHtml::_('form.token'); ?>
	</div>			      
