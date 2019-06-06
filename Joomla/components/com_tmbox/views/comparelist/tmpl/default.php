<?php

defined('_JEXEC') or die;
 JFactory::getLanguage()->load('com_tmbox');
VmConfig::loadConfig();
VmConfig::loadJLang('com_virtuemart', true);
vmJsApi::addJScript("/components/com_tmbox/assets/scripts/tmbox.js",false,false);
?>
<div id="com_virtuemart" class="compare_box">
<h3 class="module-title"><i class="fa fa-exchange"></i><?php echo JText::_('COM_COMPARE_COMPARE_PRODUCT') ?></h3>
<?php
if (!empty($this->products)) { 
    $columncount = count($this->products);
?>
<div class="table-responsive">
    <table class="table table-bordered column<?php echo $columncount; ?>">
        <thead class="table-title-bg">
            <tr>
                <th colspan="<?php echo count($this->products) + 1; ?>" class="table-title">
                    <strong><?php echo JText::_('COM_COMPARE_PRODUCT_DETAILS') ?></strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo JText::_('COM_COMPARE_PRODUCT') ?></td>
                  <?php
                    $ratingModel = VmModel::getModel('ratings');
                    $showRating = $ratingModel->showRating();
                     //var_dump( $showRating);
                   foreach ($this->products as $product) { ?>
                <td class="text-center key<?php echo $product->virtuemart_product_id; ?>">
                    <div class="product-thumb">
                        <a class="compare_del" title="remove"  onclick="TmboxRemoveCompare('<?php echo $product->virtuemart_product_id; ?>');"><i class="fa fa-times"></i><?php echo JText::_('COM_COMPARE_REMOVE') ?></a>
                        <?php if ($product->images[0]) { ?>
                            <div class="image">
                                <?php echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false); ?>
                            </div>
                        <?php } ?>
                        <div class="price">
                            <?php 
                            if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
                            $currency = CurrencyDisplay::getInstance( );
                            echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
                        </div>
                        <div class="name">
                            <h5 class="item_name product_title">
                            <?php $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                                $product->virtuemart_category_id); ?>

                            <a href="<?php echo $url ?>"><?php echo shopFunctionsF::limitStringByWord($product->product_name,'100', '...'); ?></a> 
                            </h5> 
                        </div>

                       <div class="vm-rating">
                        <?php

                           // $showRating
                            //print_r($product);
                            echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$showRating, 'product'=>$product)); ?>
                           
                        </div>
                    </div>
                </td>
            <?php } ?>
            </tr> 
            <tr>
                <td><?php echo JText::_('COM_COMPARE_SKU') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="key<?php echo $product->virtuemart_product_id;?>"><?php echo $product->product_sku ?></td>
                <?php } ?>
            </tr>  
            <tr>
            <td><?php echo JText::_('COM_COMPARE_BRAND') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="key<?php echo $product->virtuemart_product_id;?>"><?php echo $product->mf_name; ?></td>
                <?php } ?>
            </tr> 
             <tr>
                <td><?php echo JText::_('COM_COMPARE_AVAILABILITY') ?></td>
                <?php foreach ($this->products as $product) { ?>
                <td class="key<?php echo $product->virtuemart_product_id;?>"><?php echo $product->product_availability; ?></td>
                <?php } ?>
            </tr>
             <tr>
                <td><?php echo JText::_('COM_COMPARE_SUMMARY') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="description key<?php echo $product->virtuemart_product_id;?>"><?php echo $product->product_s_desc; ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_COMPARE_WEIGHT') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="key<?php echo $product->virtuemart_product_id;?>"><?php if($product->product_weight){ echo  round($product->product_weight, 2).$product->product_weight_uom;} ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_COMPARE_DIMENSIONS') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="key<?php echo $product->virtuemart_product_id;?>"><?php if($product->product_length && $product->product_width && $product->product_height ){
                        if($product->product_length) {echo round($product->product_length, 2).$product->product_lwh_uom.' x'; } ?>
                        <?php if($product->product_width) {echo round($product->product_width, 2).$product->product_lwh_uom.' x';} ?>
                        <?php if($product->product_height) {echo round($product->product_height, 2).$product->product_lwh_uom;} ?>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
             <tr>
                <td><?php echo JText::_('COM_COMPARE_PACKAGING') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="key<?php echo $product->virtuemart_product_id;?>"><?php if($product->product_packaging){ echo  round($product->product_packaging, 2).$product->product_unit;} ?></td>
                <?php } ?>
            </tr>
             <tr>
                <td><?php echo JText::_('COM_COMPARE_UNITS_IN_BOX') ?></td>
                <?php foreach ($this->products as $product) { ?>
                    <td class="key<?php echo $product->virtuemart_product_id;?>"><?php if($product->product_box){ echo  $product->product_box;} ?></td>
                <?php } ?>
            </tr>
            <?php
            $customfieldsModel = VmModel::getModel ('Customfields');
            foreach ($this->products as $product) {
                 if ($product->customfields){

                if (!class_exists ('vmCustomPlugin')) {
                    require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');
                }
                $customfieldsModel -> displayProductCustomfieldFE ($product, $product->customfields);
            }
            }?>
        </tbody>
            <?php
            $compareid = array();
            foreach ($this->products as $product) { 
                if (isset($product->customfields)) {
                    foreach ($product->customfields as $field) {
                        if (($field->field_type == 'E' && $field->custom_value !== 'youtube') || $field->field_type == 'P' || $field->field_type == 'S' || $field->field_type == 'I' || $field->field_type == 'B' || $field->field_type == 'D' || $field->field_type == 'G') {
                            $compareFields[$field->virtuemart_custom_id] = $field->custom_title;
                            if ($field->custom_parent_id == $customidparent) {
                               
                            }
                             if (isset($compareFields[$field->virtuemart_custom_id]) && $field->display) {
                                $compareFieldsProduct[$product->virtuemart_product_id][$field->virtuemart_custom_id] = $field->display;
                                //var_dump($compareFieldsProduct);
                            }
                        }
                    }
                    //var_dump($compareid);
                } 
             }
            ?>
        <?php
        $row = 1;
         if (isset($compareFields)) {
            foreach ($compareFields as $field_no => $field_name) {
                $table[$row][0] = $field_name;
                $row++;
            }
        }
        $rowall = $row;
        $col = 1;
        $tdclass[0] = '';
    foreach ($this->products as $product) { 
        $row = 1;
         $tdclass[$col] = 'key' . $product->virtuemart_product_id;
        $table[$row][$col] = '';
        if (isset($compareFields)) {
            foreach ($compareFields as $field_no => $field_name) {
                if (isset($compareFieldsProduct[$product->virtuemart_product_id][$field_no])) {
                    $fld = $compareFieldsProduct[$product->virtuemart_product_id][$field_no];
                    print_r($fieldtype);
                   $table[$row][$col] = $fld;
                } else {
                    $table[$row][$col] = '';
                }
                $row++;
            }
        }
        $col++;
    }
    for ($r = 0; $r < $rowall; $r++) {
        $trclass[$r] = ' none';
        for ($c = 2; $c < $col; $c++) {
            if ($table[$r][$c] != $table[$r][$c - 1]) {
                $trclass[$r] = ' tr_fields';
            }

        }

    }    
    ?>  

        <tbody id="compare_fields">
        <?php     
        for ($r = 1; $r < $rowall; $r++) {
            echo '<tr class="items fields' . $r . '">';
            for ($c = 0; $c < $col; $c++) {
                $class = $tdclass[$c];
                if ($c > 0) $class .= $trclass[$r];
                echo '<td class="castomfields ' . $class . '" >';
                if (isset($table[$r][$c]))
                    echo $table[$r][$c];
                else
                    echo '';
                echo '</td>';
            }
            echo '</tr>';
        }
        ?>
        </tbody>
         <tr>
            <td><?php echo JText::_('COM_COMPARE_ACTION') ?></td>
            <?php foreach ($this->products as $product) { ?>
                <td class="text-center key<?php echo $product->virtuemart_product_id;?>">
                    <?php 
                    echo shopFunctionsF::renderVmSubLayout('variants',array('product'=>$product,'row'=>0));
                    ?>
                    <?php 
                    echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'row'=>0));
                     ?>
                     <?php  if (is_file(JPATH_BASE . "/components/com_tmbox/template/mywishlists.tpl.php")) : ?>
                <div style="display:<?php echo $styleblockadd; ?>;"
                    class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . "/components/com_tmbox/template/mywishlists.tpl.php"); ?>
                </div>
            <?php endif; ?>
                </td>
            <?php } ?>
        </tr>   
    </table>
                     
</div> 
<div class="module-title compare no-products" style="display:none;">
    <i class="fa fa-info-circle"></i><?php echo JText::_('COM_TMBOX_ITEMS_NO_PRODUCTS'); ?>
</div>                           
<?php } else { ?>
     <div class="module-title compare no-products">
        <i class="fa fa-info-circle"></i><?php echo JText::_('COM_TMBOX_ITEMS_NO_PRODUCTS'); ?>
    </div>
<?php } ?>
 <?php  
 /*

    <h3 class="module-title compare no-products" style="display:none;">
        <i class="fa fa-info-circle"></i><?php echo JText::_('COM_TMBOX_ITEMS_NO_PRODUCTS'); ?>
    </h3>
<?php
} else {
    echo '<h3 class="module-title compare no-products" ><i class="fa fa-info-circle"></i>' . JText::_('COM_TMBOX_ITEMS_NO_PRODUCTS') . '</h3>';
}

*/?>
</div>
    