<?php
/**
 * @version    CVS: 4.2.2
 * @package    Com_Mdimpressum_pro
 * @author     Axel Metz <office@megrodesign.com>
 * @copyright  Copyright (C) 2016. Alle Rechte vorbehalten.
 * @license    GNU General Public License Version 2 oder sp&auml;ter; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
if($this->params->get("bootstrapsupp") == 1):
//$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/bootstrap.css');
$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
endif;
if($this->params->get("fontawaysupp") == 1):
$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/font-awesome.css');
$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/font-awesome.min.css');
endif;
$document->addStyleSheet('components/com_mdimpressum_pro/assets/css/impressum.css');
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

$anzeigeimpressum = $this->params->get("anzeigeimpressum");
$anzeigeinhalt = $this->params->get("anzeigeimpressum2");
$anzeigetechnisch = $this->params->get("anzeigeimpressum3");
$bank = $this->params->get("anzeigeimpressum4");
$template = $this->params->get("anzeigeimpressum5");
$linkds = $this->params->get("anzeigeimpressum6");
 $coloricon = $this->params->get("coloricon");
$addcss = $this->params->get("cssover");
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_mdimpressum_pro');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_mdimpressum_pro')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<style type="text/css">

<?php if ( $addcss != '' ) echo $addcss ;?>
	</style>

<?php if ($this->item) : ?>


<div class="row">   
 <div class="<?php echo $this->params->get("headsize"); ?>">
  <div class="col-md-8">
<span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo $this->params->get("uberimpressum"); ?></span></div>
  </div></div>
    
<span class="fa fa-2x" style="color: <?php echo $coloricon; ?>;"><i class="fa fa-home"></i></span>

 <div class="row">
  <div class="col-md-4"> 
  <address>
  <strong><?php echo $this->item->firma; ?></strong><br />
 <?php if(empty($this->item->vrnummer)) {?>
<?php }
else  {?>
 <?php echo $this->item->vrnummer; ?> 
 <?php }?> <br />
   <strong><?php echo $this->item->vorname; ?> <?php echo $this->item->name; ?></strong><br />
  <?php echo $this->item->str1; ?><br />
  <?php echo $this->item->plz1; ?> <?php echo $this->item->ort1; ?><br />
  <br />
  <abbr title="Telefon">Tel:</abbr> <br />
  <abbr title="E-Mailadresse">E-Mail:</abbr>
  </address>
  </div>
  <div class="col-md-6">
  <?php if(empty($this->item->vtel2)) {?>
<?php }
else  {?>
<abbr title="2. Telefonnummer">Tel:</abbr> <?php echo $this->item->tel2; ?><br /><?php }?>
 <?php if(empty($this->item->email2)) {?>
<?php }
else  {?>
<abbr title="2. E-Mailadresse">E-Mail:</abbr> <a href="mailto:<?php echo $this->item->email2; ?>"><?php echo $this->item->email2; ?></a><br /><?php }?>
 <?php if(empty($this->item->fax1)) {?>
<?php }
else  {?>
<abbr title="Faxnummer">Fax:</abbr> <?php echo $this->item->fax1; ?><br /><?php }?>
<?php if(empty($this->item->mobil1)) {?>
<?php }
else  {?>
<abbr title="Mobilfunknummer">Mobil:</abbr> <?php echo $this->item->mobil1; ?><br /><?php }?>
<?php if(empty($this->item->website1)) {?>
<?php }
else  {?>
<abbr title="Webseite">Web:</abbr> <a href="http://<?php echo $this->item->website1; ?>" target="_blank"><?php echo $this->item->website1; ?></a><br /><?php }?>
  </div> 
     
     
 </div>
<?php if($anzeigeimpressum == 0): 

else: ?>
<div class="row">   
 <div class="col-md-1">
 <i class="fa fa-users fa-2x"  style="color: <?php echo $this->params->get("iconcolor"); ?>;"></i>
 </div>
  <div class="col-md-8">
      <div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo $this->params->get("ubervertretung"); ?></span></div>
 </div>
</div> 
</div>
    
<div class="row">    
    
<?php if(empty($this->item->vnamen)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VNAMEN'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vnamen; ?></div>
 </div>
<?php }?>

<?php if(empty($this->item->vnamen2)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VNAMEN2'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vnamen2; ?></div> 
 </div>
<?php }?>

<?php if(empty($this->item->vsitz)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VSITZ'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vsitz; ?></div> 
 </div>
<?php }?>    

<?php if(empty($this->item->vearnr)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VEARNR'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vearnr; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vlizbeh)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VLIZBEH'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vlizbeh; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vfinanz)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VFINANZ'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vfinanz; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vstr)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VSTR'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vstr; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vplz)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VPLZ'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vplz; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vort)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VORT'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vort; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vemail)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VEMAIL'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vemail; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vtel)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VTEL'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vtel; ?></div> 
 </div>
<?php }?>   
<?php if(empty($this->item->vberuf)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VBERUF'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vberuf; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vkammer)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VKAMMER'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vkammer; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vgesetzlbest)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VGESETZLBEST'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vgesetzlbest; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vland)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VLAND'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vland; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vaufsicht)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VAUFSICHT'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vaufsicht; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vhaft)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VHAFT'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vhaft; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vversich)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VVERSICH'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vversich; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vversichnr)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VVERSICHNR'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vversichnr; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vpersons)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VPERSONS'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vpersons; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vsachs)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VSACHS'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vsachs; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vregg)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VREGG'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vregg; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->vreggnr)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VREGGNR'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vreggnr; ?></div> 
 </div>
<?php }?>
<?php if(empty($this->item->ustid)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_USTID'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->ustid; ?></div> 
 </div>
<?php }?>
<?php if(empty($this->item->vwirtid)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_VWIRTID'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->vwirtid; ?></div> 
 </div>
     <?php }?></div>

<?php endif; ?>


<?php if($anzeigetechnisch == 0): 

else: ?>

  <div class="row"> 
 <div class="col-md-1">
 <i class="fa fa-users fa-2x"  style="color: <?php echo $this->params->get("iconcolor"); ?>;"></i>
 </div>
  <div class="col-md-8">
      <div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo $this->params->get("ubertechnisch"); ?></span></div>
 </div>
</div>    
  </div>   
<div class="row"> 
<?php if(empty($this->item->tname)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->tvorname; ?> <?php echo $this->item->tname; ?></div>
 </div>
<?php }?>

<?php if(empty($this->item->tstr)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TSTR'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->tstr; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->tplz)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TPLZ'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->tplz; ?></div> 
 </div>
<?php }?>    
 <?php if(empty($this->item->tort)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TORT'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->tort; ?></div> 
 </div>
<?php }?>   
<?php if(empty($this->item->temail)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TEMAIL'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->temail; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->ttel)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TTEL'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->ttel; ?></div> 
 </div>
     <?php }?></div>
<?php endif; ?>
<?php if($anzeigeinhalt == 0):

else: ?>


 <div class="row"> 
 <div class="col-md-1">
 <i class="fa fa-reorder fa-2x"  style="color: <?php echo $this->params->get("iconcolor"); ?>;"></i>
 </div>
  <div class="col-md-8">
      <div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo $this->params->get("uberinhalt"); ?></span></div>
 </div>
</div>    
  </div>   
<div class="row">   
<?php if(empty($this->item->inname)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->invorname; ?> <?php echo $this->item->inname; ?></div>
 </div>
<?php }?>

<?php if(empty($this->item->instr)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_INSTR'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->instr; ?></div> 
 </div>
<?php }?>
<?php if(empty($this->item->inplz)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_INPLZ'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->inplz; ?></div> 
 </div>
<?php }?>
<?php if(empty($this->item->inort)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_INORT'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->inort; ?></div> 
 </div>
<?php }?>

<?php if(empty($this->item->inemail)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_INEMAIL'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->inemail; ?></div> 
 </div>
<?php }?>
<?php if(empty($this->item->intel)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_INTEL'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->intel; ?></div> 
 </div>
<?php }?></div>

<?php endif; ?>
<?php if($bank == 0): 

else: ?>

 <div class="row"> 
 <div class="col-md-1">
 <i class="fa fa-university fa-2x"  style="color: <?php echo $this->params->get("iconcolor"); ?>;"></i>
 </div>
  <div class="col-md-8">
      <div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">Bankdaten</span></div>
 </div>
</div>    
  </div>   
<div class="row"> 
<?php if(empty($this->item->bankinhaber)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_BANKINHABER'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->bankinhaber; ?></div> 
 </div>
<?php }?>
    <?php if(empty($this->item->bankname)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_BANKNAME'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->bankname; ?></div> 
 </div>
<?php }?>
<?php if(empty($this->item->bankiban)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_BANKIBAN'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->bankiban; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->bankswift)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_BANKSWIFT'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->bankswift; ?></div> 
 </div>
<?php }?></div>

<?php endif; ?>

<?php if($template == 0): 

else: ?>
 <div class="row"> 
 <div class="col-md-1">
 <i class="fa fa-desktop fa-2x"  style="color: <?php echo $this->params->get("iconcolor"); ?>;"></i>
 </div>
  <div class="col-md-8">
      <div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">Template</span></div>
 </div>
</div>    
  </div>   
<div class="row">    
<?php if(empty($this->item->templname)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TEMPLNAME'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->templname; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->templersteller)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TEMPLERSTELLER'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->templersteller; ?></div> 
 </div>
<?php }?>    
<?php if(empty($this->item->templwebsite)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_TEMPLWEBSITE'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><?php echo $this->item->templwebsite; ?></div> 
 </div>
<?php }?></div>
<?php endif; ?>



<?php if($linkds == 0): 

else: ?>
 <div class="row"> 
 <div class="col-md-1">
 <i class="fa fa-desktop fa-2x"  style="color: <?php echo $this->params->get("iconcolor"); ?>;"></i>
 </div>
  <div class="col-md-8">
      <div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">Datenschutzlink</span></div>
 </div>
</div>    
  </div>   
<div class="row">     
    
    
<?php if(empty($this->item->linkdatenschutz)) {?>
<?php }
else  {?> 
<div class="row2">
 <div class="<?php echo $this->params->get("spaltea"); ?>"><?php echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_LINKDATENSCHUTZ'); ?></div><div class="<?php echo $this->params->get("spalteb"); ?>"><a href="http://<?php echo $this->item->linkdatenschutz; ?>" target="_blank"><?php echo $this->item->linkdatenschutz; ?></a></div> 
 </div>
<?php }?>
</div>
   <?php endif; ?> 
 <div class="row">
   
    <div class="col-md-12">Copyright &copy; <?php echo date("Y"); ?> - <?php echo $this->item->firma; ?> - Alle Rechte vorbehalten.  Alle Angaben ohne Gew&auml;hr. </div>
    </div> 
<?php if($hinter == 0): 

else: ?>
   

 <!-- Datenschutz laden -->

 <?php
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__mdimpressum_pro_daten');
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $row) :
endforeach;?>
<div class="row">
    <div class="col-md-3"> </div>
         <div class="col-md-6"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">Datenschutzerkl&auml;rung</span></div></div></div>

<?php if($datenschutz1 == 0): 

else: ?>
<?php if(empty($row->datenschutz)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutz'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->datenschutz; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($amazon == 0): 

else: ?>
<?php if(empty($row->amazon)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung des Amazon Partnerprogramms'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->amazon; ?></div></div>
    </div>
<br /><br />
<?php }?> 
 <?php endif; ?>
<?php if($etracker == 0): 

else: ?>
<?php if(empty($row->etracker)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von etracker'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->etracker; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>

<?php if($facebook == 0): 

else: ?>
<?php if(empty($row->facebook)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Facebook-Plugins (Like-Button)'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->facebook; ?></div></div>
    </div>
<br /><br />
<?php }?> 
<?php endif; ?>
<?php if($googleadsense == 0): 

else: ?>
<?php if(empty($row->google_adsense)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Google Adsense'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->google_adsense; ?></div></div>
    </div>
<br /><br />
<?php }?> 
<?php endif; ?>
<?php if($googleanalytics == 0): 

else: ?>
<?php if(empty($row->google_analytics)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Google Analytics'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->google_analytics; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($googleplus == 0): 

else: ?>
<?php if(empty($row->googleplus)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Google +1'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->googleplus; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($remark == 0): 

else: ?>
<?php if(empty($row->googleanalyticsremark)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Google Analytics Remarketing'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->googleanalyticsremark; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
<?php if($instagram == 0): 

else: ?>
<?php if(empty($row->instagram)) {?>
<?php }
else  {?>
<div class="row">
     <div class="col-md-1">

  <i class="fa fa-2x"  style="font-weight:bold;"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;">&sect;</span></i>
     </div><div class="col-md-8"><div class="<?php echo $this->params->get("header_tag"); ?>"><div class="<?php echo $this->params->get("headsize"); ?>"><span style="color:<?php echo $this->params->get("headfarbe"); ?>;"><?php echo JText::_('Datenschutzerkl&auml;rung f&uuml;r die Nutzung von Instagram'); ?></span></div> </div></div>
			<div class="col-md-12"><div align="<?php echo $this->params->get("datenschutzrichtung"); ?>"><?php echo $row->instagram; ?></div></div>
    </div>
<br /><br />
<?php }?>
<?php endif; ?>
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

<!-- Datenschutz3 laden -->
 
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
<!-- Rechte laden -->
 
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
<?php endif; ?>

			<?php echo $this->item->werbungfooter; ?>


<?php echo ' Copyright &copy; '.date("Y");?> megrodesign.com   &nbsp; &nbsp;Alle Rechte vorbehalten.  &nbsp; &nbsp;Das Joomla Impressum von  &nbsp;<a href="http://www.megrodesign.com" target="_blank">MEGRODESIGN.COM</a>


<!--
<tr>
			<th><?php //echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_FREI1'); ?></th>
			<td><?php //echo $this->item->frei1; ?></td>
</tr>
<tr>
			<th><?php //echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_FREI2'); ?></th>
			<td><?php //echo $this->item->frei2; ?></td>
</tr>
<tr>
			<th><?php //echo JText::_('COM_MDIMPRESSUM_PRO_FORM_LBL_MDIMPRESSUM_FREI3'); ?></th>
			<td><?php //echo $this->item->frei3; ?></td>
</tr>
-->
		
	
	<?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&task=mdimpressum.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_MDIMPRESSUM_PRO_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_mdimpressum_pro')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_mdimpressum_pro&task=mdimpressum.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_MDIMPRESSUM_PRO_DELETE_ITEM"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_MDIMPRESSUM_PRO_ITEM_NOT_LOADED');
endif;
