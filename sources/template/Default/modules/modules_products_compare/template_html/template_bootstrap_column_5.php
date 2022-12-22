<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;
?>
<div class="col-md-<?php echo $bootstrap_column; ?> col-md-<?php echo $bootstrap_column; ?>">
  <div style="padding-top:1rem;"></div>
  <div class="card-deck-wrapper">
    <div class="card-deck">
      <div class="card" style="width: 18rem;">
        <div class="card-block">
          <div class="card-header">
            <div class="ModulesProductsCompareBoostrapColumn5Title"><h3><?php echo HTML::link($products_name_url, $products_name); ?></h3></div>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item ">
              <div class="card-img-top ModulesProductsCompareBoostrapColumn5Image" style="height:150px; padding-bottom: 10px;"><?php echo $products_image . $ticker; ?> </div>
            </li>
            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5TextPrice<?php echo CLICSHOPPING::getDef('text_price') . ' ' . $product_price; ?></div>
            </li>
<?php
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_MODEL == 'True') {
?>
            <li class="list-group-item  ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5Model"><?php echo $products_model; ?></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_WEIGHT == 'True') {
?>
            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5Weight"><?php echo $products_weight; ?></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_SHORT_DESCRIPTION == 'True') {
?>
            <li class="list-group-item ">
              <div class="ModulesProductsCompareBoostrapColumn5ShortDescription" style="height:100px;"><h3><?php echo $products_short_description; ?></h3></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_MANUFACTURER == 'True') {
?>
            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5Brand"><?php echo $products_manufacturers; ?></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_STOCK != 'none') {
?>
            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5Stock"><?php echo $products_stock; ?></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_PACKAGING == 'True') {
?>
            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5Packaging"><?php echo $products_packaging; ?></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_LENGTH == 'True') {
?>

            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5ProductsLength"><?php echo $products_length; ?></div>
            </li>
<?php
  }
  if (MODULE_PRODUCTS_COMPARE_DISPLAY_MIN_QTY == 'True') {
?>
            <li class="list-group-item ModulesProductsCompareBoostrapColumn5GroupItem">
              <div class="ModulesProductsCompareBoostrapColumn5ProductsQuantityUnit"><?php echo $min_order_quantity_products_display; ?></div>
            </li>
<?php
            }
?>

            <li class="list-group-item ">
              <?php echo $form; ?>
              <div class="form-group form-group-center">
                <span class="ModulesProductsCompareBoostrapColumn5QuantityMinOrder"><?php echo $input_quantity; ?>&nbsp; </span>
                <span class="ModulesProductsCompareBoostrapColumn5ViewDetails"><?php echo $button_small_view_details; ?>&nbsp; </span>
                <span class="ModulesProductsCompareBoostrapColumn5SubmitButton"><label for="ModulesProductsCompareBoostrapColumn5SubmitButton"><?php echo $submit_button; ?></label></span>
              </div>
              <?php echo $endform; ?>

              <?php echo $formRemove; ?>
                <div class="ModulesProductsCompareBoostrapColumn5Remove"><?php echo HTML::button(CLICSHOPPING::getDef('button_remove'), null, null, 'danger', null, 'md'); ?></div>
              <?php echo $endform; ?>
            </li>
          </ul>
        </div>
      </div>
     </div>
  </div>
</div>
