<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDBO();
// the select list should include the vendor currency which is the currency in which the product prices are displayed by default.
$q  = 'SELECT CONCAT(`vendor_accepted_currencies`, ",",`vendor_currency`) AS all_currencies, `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id`='.$vendorId;
$db->setQuery($q);
$vendor_currency = $db->loadAssoc();


$virtuemart_currency_id = $mainframe->getUserStateFromRequest( "virtuemart_currency_id", 'virtuemart_currency_id',JRequest::getInt('virtuemart_currency_id', $vendor_currency['vendor_currency']) );
/* table vm_currency */
$q = 'SELECT `virtuemart_currency_id`,`currency_symbol`,`currency_code_3`,CONCAT_WS(" ",`currency_name`) as currency_txt
FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id` IN ('.$vendor_currency['all_currencies'].') and (`virtuemart_vendor_id` = "'.$vendorId.'" OR `shared`="1") AND published = "1" ORDER BY `ordering`,`currency_name`';
$db->setQuery($q);
$currencies = $db->loadObjectList();

?>
<div class="module-currencies">
<?php echo $text_before ?>
<!-- Currency Selector Module -->
<div id="currencies" class="box-currencies">
        <div class="heading">
        <?php foreach ($currencies as $currency) { ?>
            <?php if ($currency->virtuemart_currency_id == $virtuemart_currency_id) { ?>
            <span><?php echo $currency->currency_symbol; ?></span>
             <span><?php echo $currency->currency_code_3; ?></span>
        <?php } } ?>
        </div>
        <div class="dropdown-menu">
            <form id="formcurrencies" action="<?php echo vmURI::getCleanUrl() ?>" method="post" >
            	<ul class="list">
            <?php foreach ($currencies as $currency) { ?>
                <?php if ($currency->virtuemart_currency_id == $virtuemart_currency_id) { ?>
                    <li><a  class="active" title="<?php echo $currency->currency_txt; ?>"><?php echo $currency->currency_symbol; ?>&nbsp;<span><?php echo $currency->currency_txt; ?></span></a></li>
                <?php } else { ?>
                <li><a title="<?php echo $currency->currency_txt; ?>" onClick="jQuery('input[name=\'virtuemart_currency_id\']').attr('value', '<?php echo $currency->virtuemart_currency_id; ?>').submit(); jQuery(this).parent().parent().parent().submit();"><?php echo $currency->currency_symbol; ?>&nbsp;<span><?php echo $currency->currency_txt; ?></span></a></li>
                <?php } ?>
            <?php } ?>
            <input type="hidden" name="virtuemart_currency_id" value="" />
            </ul>
            </form>
        </div>
            
    </div>
</div>
 <script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#currencies .heading').click(function(){
      jQuery('#currencies').toggleClass('open')
    });
      jQuery(document).on('click touchmove',function(e) {
          var container = jQuery("#currencies");
          if (!container.is(e.target)
              && container.has(e.target).length === 0 && container.hasClass('open'))
          {
              jQuery('#currencies').toggleClass('open')
          }
      })

  })
</script>