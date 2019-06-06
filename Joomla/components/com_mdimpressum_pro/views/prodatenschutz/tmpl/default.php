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

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/impressum.css');
if($this->params->get("bootstrapsupp") == 1):

//$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/bootstrap.min.css');
endif;
//if($this->params->get("fontawaysupp") == 1):
$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/font-awesome.css');
$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/font-awesome.min.css');
$hinter = $this->params->get("anzeigedatenschhinter");
$datenschutz1 = $this->params->get("datenschutz");
$amazon = $this->params->get("amazon");
$etracker = $this->params->get("etracker");
$facebook = $this->params->get("facebook");
$googleadsense = $this->params->get("googleadsense");
$googleanalytics = $this->params->get("googleanalytics");
$googleplus = $this->params->get("googleplus");
$remark = $this->params->get("remark");
$instagram = $this->params->get("instagram");
$linkedin = $this->params->get("linkedin");
$pinterest = $this->params->get("pinterest");
$piwik = $this->params->get("piwik");
$tumblr = $this->params->get("tumblr");
$twitter = $this->params->get("twitter");
$xing = $this->params->get("xing");
$als = $this->params->get("als");
$shop = $this->params->get("shop");
$daten = $this->params->get("daten");
$cookies = $this->params->get("cookies");
$slv = $this->params->get("slv");
$als = $this->params->get("als");
$kontaktformular = $this->params->get("kontaktformular");
$werbemails = $this->params->get("werbemails");
$newsletterdaten = $this->params->get("newsletterdaten");
$registrierung = $this->params->get("registrierung");
$verarb = $this->params->get("verarb");
$haftung = $this->params->get("haftung");
$document = JFactory::getDocument();
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_mdimpressum_pro');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_mdimpressum_pro')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

	<div class="item_fields">
		
			
<div class="row">
    <div class="col-md-3"> </div>
         <div class="col-md-6"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">Datenschutzerkl&auml;rung</span></div></div></div>

<?php if($datenschutz1 == 0): 

else: ?>
<?php if(empty($this->item->datenschutz)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-6"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutz'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->datenschutz; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />


<?php if($amazon == 0): 

else: ?>
<?php if(empty($this->item->amazon)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung des Amazon Partnerprogramms'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->amazon; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($etracker == 0): 
else: ?>
<?php if(empty($this->item->etracker)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von etracker'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->etracker; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($facebook == 0): 
else: ?>
<?php if(empty($this->item->facebook)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von Facebook-Plugins (Like-Button)'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->facebook; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($googleadsense == 0): 
else: ?>
<?php if(empty($this->item->google_adsense)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von Google Adsense'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->google_adsense; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($googleanalytics == 0): 
else: ?>
<?php if(empty($this->item->google_analytics)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von Google Analytics'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->google_analytics; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($googleplus == 0): 
else: ?>
<?php if(empty($this->item->googleplus)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von Google +1'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->googleplus; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($remark == 0): 
else: ?>
<?php if(empty($this->item->googleanalyticsremark)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von Google Analytics Remarketing'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->googleanalyticsremark; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />
<?php if($instagram == 0): 
else: ?>
<?php if(empty($this->item->instagram)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">
  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerklärung für die Nutzung von Instagram'); ?></span></div> </div></div>
<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $this->item->instagram; ?></div></div>
    </div>
<?php }?>
<?php endif; ?>
<br /><br />


<!-- Datenschutz2 laden -->
 
 <?php
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__mdimpressum_pro_daten2');
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $row) :
endforeach;?>
<?php if($linkedin == 0): 

else: ?>
<?php if(empty($row->linkedin)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von LinkedIn'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->linkedin; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($pinterest == 0): 

else: ?>
<?php if(empty($row->pinterest)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Pinterest'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->pinterest; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($piwik == 0): 

else: ?>
<?php if(empty($row->piwik)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Piwik'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->piwik; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>

<?php if($tumblr == 0): 

else: ?>
<?php if(empty($row->tumblr)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Tumblr'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->tumblr; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($twitter == 0): 

else: ?>
<?php if(empty($row->twitter)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Twitter'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->twitter; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($xing == 0): 

else: ?>
<?php if(empty($row->xing)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Xing'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->xing; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($als == 0): 

else: ?>
<?php if(empty($row->auslosperr)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Auskunft, L&ouml;schung, Sperrung'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->auslosperr; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($shop == 0): 

else: ?>
<?php if(empty($row->shop)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Daten&uuml;bermittlung bei Vertragsschluss f&uuml;r Online-Shops, H&auml;ndler und Warenversand'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->shop; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($daten == 0): 

else: ?>
<?php if(empty($row->datenubermi)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Daten&uuml;bermittlung bei Vertragsschluss f&uuml;r Dienstleistungen und digitalen Inhalten'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->datenubermi; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>

<?php
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__mdimpressum_pro_daten3');
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $row) :
endforeach;?>
<?php if($cookies == 0): 

else: ?>
<?php if(empty($row->cookies)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Cookies'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->cookies; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($slv == 0): 

else: ?>
<?php if(empty($row->serverlog)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Server-Log-Files'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->serverlog; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($kontaktformular == 0): 

else: ?>
<?php if(empty($row->kontform)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Kontaktformular'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->kontform; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($werbemails == 0): 

else: ?>
<?php if(empty($row->werbemails)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Widerspruch Werbe-Mails'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->werbemails; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($newsletterdaten == 0): 

else: ?>
<?php if(empty($row->newsletterdaten)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Newsletterdaten'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->newsletterdaten; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($registrierung == 0): 

else: ?>
<?php if(empty($row->reg)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Registrierung auf dieser Webseite'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->reg; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($verarb == 0): 

else: ?>
<?php if(empty($row->verarbdaten)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Verarbeiten von Daten (Kunden- und Vertragsdaten)'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->verarbdaten; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>

<?php
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__mdimpressum_pro_law');
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $row) :
endforeach;?>

<?php if(empty($row->haftung)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Haftungsausschluss (Disclaimer)'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->haftung; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php if(empty($row->urheberrecht)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Urheberrecht'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->urheberrecht; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php if(empty($row->bildquellen)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Bildquellenangaben'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->bildquellen; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php if(empty($row->bildrechte)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Bildrechte'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->bildrechte; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php if(empty($row->sonstige)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Sonstiges'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->sonstige; ?></div></div>
    </div>
<br /><br />
<?php }?>




	</div>
	<?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&task=prodatenschutz.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_MDIMPRESSUM_PRO_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_mdimpressum_pro')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&task=prodatenschutz.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_MDIMPRESSUM_PRO_DELETE_ITEM"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_MDIMPRESSUM_PRO_ITEM_NOT_LOADED');
endif;
