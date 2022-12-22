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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class pc_products_compare {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_products_compare_title');
      $this->description = CLICSHOPPING::getDef('module_products_compare_description');

      if (defined('MODULE_PRODUCTS_COMPARE_STATUS')) {
        $this->sort_order = MODULE_PRODUCTS_COMPARE_SORT_ORDER;
        $this->enabled = (MODULE_PRODUCTS_COMPARE_STATUS == 'True');
      }
    }

    public function execute() {
      $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');
      $CLICSHOPPING_Template = Registry::get('Template');
      $CLICSHOPPING_ProductsFunctionTemplate = Registry::get('ProductsFunctionTemplate');
      $CLICSHOPPING_ProductsAttributes = Registry::get('ProductsAttributes');

      if (isset($_SESSION['productsCompare'])) {
        $products_id_array = $_SESSION['productsCompare'];

        if (isset($_GET['Compare']) && isset($_GET['ProductsCompare']) && is_array($products_id_array)) {
          $new_prods_content = '<!-- New Products start -->' . "\n";

          $new_prods_content .= '<div class="page-header ModuleFrontPageProductsCompareHeading"><h1>' . CLICSHOPPING::getDef('module_products_compare_heading_title') . '</h1></div>';

          if (count($products_id_array) > 0 ) {
            $array_unique = array_unique($products_id_array);

// display number of short description
            $products_short_description_number = (int)MODULE_PRODUCTS_COMPARE_SHORT_DESCRIPTION;
// delete words
           $delete_word = (int)MODULE_PRODUCTS_COMPARE_SHORT_DESCRIPTION_DELETE_WORLDS;
// nbr of column to display  boostrap
            $bootstrap_column = (int)MODULE_PRODUCTS_COMPARE_COLUMNS;
// initialisation des boutons
            $size_button = $CLICSHOPPING_ProductsCommon->getSizeButton('md');

// Template define
          $filename= '';
            $filename = $CLICSHOPPING_Template->getTemplateModulesFilename($this->group .'/template_html/' . MODULE_PRODUCTS_COMPARE_TEMPLATE);

            $new_prods_content .= '<div class="d-flex flex-wrap ModuleFrontPageboxContainerNewProducts">';


            foreach ($array_unique as $products_id) {
              $products_name_url = $CLICSHOPPING_ProductsFunctionTemplate->getProductsUrlRewrited()->getProductNameUrl($products_id);
//product name
              $products_name = $CLICSHOPPING_ProductsCommon->getProductsName($products_id);
//Stock (good, alert, out of stock).
              $products_stock = $CLICSHOPPING_ProductsFunctionTemplate->getStock(MODULE_PRODUCTS_COMPARE_DISPLAY_STOCK, $products_id);
//Flash discount
             $products_flash_discount = $CLICSHOPPING_ProductsFunctionTemplate->getFlashDiscount($products_id, '<br />');
// Minimum quantity to take an order
              $min_order_quantity_products_display = $CLICSHOPPING_ProductsFunctionTemplate->getMinOrderQuantityProductDisplay($products_id);
// display a message in public function the customer group applied - before submit button
              $submit_button_view = $CLICSHOPPING_ProductsFunctionTemplate->getButtonView($products_id);
// button buy
              $buy_button = HTML::button(CLICSHOPPING::getDef('button_buy_now'), null, null, 'primary', null, 'sm');
              $CLICSHOPPING_ProductsCommon->getBuyButton($buy_button);
// Display an input allowing for the customer to insert a quantity
              $input_quantity = $CLICSHOPPING_ProductsFunctionTemplate->getDisplayInputQuantity(MODULE_PRODUCTS_COMPARE_DELETE_BUY_BUTTON, $products_id);
// display the differents prices before button
              $product_price = $CLICSHOPPING_ProductsCommon->getCustomersPrice($products_id);
//Short description
              $products_short_description = $CLICSHOPPING_ProductsCommon->getProductsShortDescription($products_id, $delete_word, $products_short_description_number);

// **************************
// display the differents buttons before minorder qty
// **************************
              $submit_button = '';
              $form = '';
              $endform = '';

              if (MODULE_PRODUCTS_COMPARE_DELETE_BUY_BUTTON == 'False') {
                if ($CLICSHOPPING_ProductsCommon->getProductsMinimumQuantity($products_id) != 0 && $CLICSHOPPING_ProductsCommon->getProductsQuantity($products_id) != 0) {
                  if ($CLICSHOPPING_ProductsAttributes->getHasProductAttributes($products_id) === false) {
                    $form =  HTML::form('cart_quantity', CLICSHOPPING::link(null, 'Cart&Add' ),'post','class="justify-content-center"', ['tokenize' => true]). "\n";
                    $form .= HTML::hiddenField('products_id', $products_id);
                    if (isset($_GET['ProductsCompare'])) $form .= HTML::hiddenField('url', 'Compare&ProductsCompare');
                    $endform = '</form>';
                    $submit_button = $CLICSHOPPING_ProductsCommon->getProductsBuyButton($products_id);
                  }
                }
              }

// Quantity type
              $products_quantity_unit = $CLICSHOPPING_ProductsFunctionTemplate->getProductQuantityUnitType($products_id);

// **************************************************
// Button Free - Must be above getProductsExhausted
// **************************************************
              if ($CLICSHOPPING_ProductsCommon->getProductsOrdersView($products_id) != 1 && NOT_DISPLAY_PRICE_ZERO == 'false') {
                $products_name_url = $CLICSHOPPING_ProductsFunctionTemplate->getProductsUrlRewrited()->getProductNameUrl($products_id);

                $submit_button = HTML::button(CLICSHOPPING::getDef('text_products_free'), '', $products_name_url, 'danger');
                $min_quantity = 0;
                $form = '';
                $endform = '';
                $input_quantity ='';
                $min_order_quantity_products_display = '';
              }

// **************************
// Display an information if the stock is exhausted for all groups
// **************************
              if (!empty($CLICSHOPPING_ProductsCommon->getProductsExhausted($products_id))) {
                $submit_button = $CLICSHOPPING_ProductsCommon->getProductsExhausted($products_id);
                $min_quantity = 0;
                $input_quantity = '';
                $min_order_quantity_products_display = '';
              }

// See the button more view details
               $button_small_view_details = $CLICSHOPPING_ProductsFunctionTemplate->getButtonViewDetails(MODULE_PRODUCTS_COMPARE_DELETE_BUY_BUTTON, $products_id);
// Display the image
              $products_image = $CLICSHOPPING_ProductsFunctionTemplate->getImage(MODULE_PRODUCTS_COMPARE_IMAGE_MEDIUM, $products_id);
// Ticker Image
              $products_image .= $CLICSHOPPING_ProductsFunctionTemplate->getTicker(MODULE_PRODUCTS_COMPARE_TICKER, $products_id, 'ModulesFrontPageTickerBootstrapTickerSpecial', 'ModulesFrontPageTickerBootstrapTickerFavorite', 'ModulesFrontPageTickerBootstrapTickerFeatured', 'ModulesFrontPageTickerBootstrapTickerNew');

              $ticker = $CLICSHOPPING_ProductsFunctionTemplate->getTickerPourcentage(MODULE_PRODUCTS_COMPARE_POURCENTAGE_TICKER, $products_id, 'ModulesFrontPageTickerBootstrapTickerPourcentage');

//******************************************************************************************************************
//            Options -- activate and insert code in template and css
//******************************************************************************************************************

// products model
              $products_model = $CLICSHOPPING_ProductsFunctionTemplate->getProductsModel($products_id);
// manufacturer
              $products_manufacturers = $CLICSHOPPING_ProductsFunctionTemplate->getProductsManufacturer($products_id);
// display the price by kilo
              $product_price_kilo = $CLICSHOPPING_ProductsFunctionTemplate->getProductsPriceByWeight($products_id);
// display date available
              $products_date_available =  $CLICSHOPPING_ProductsFunctionTemplate->getProductsDateAvailable($products_id);
// display products only shop
              $products_only_shop = $CLICSHOPPING_ProductsFunctionTemplate->getProductsOnlyTheShop($products_id);
// display products only shop
              $products_only_web = $CLICSHOPPING_ProductsFunctionTemplate->getProductsOnlyOnTheWebSite($products_id);
// display products packaging
              $products_packaging = $CLICSHOPPING_ProductsFunctionTemplate->getProductsPackaging($products_id);
// display shipping delay
                $products_shipping_delay =  $CLICSHOPPING_ProductsFunctionTemplate->getProductsShippingDelay($products_id);
// display products tag
                $tag = $CLICSHOPPING_ProductsFunctionTemplate->getProductsHeadTag($products_id);

                $products_tag = '';
                if (isset($tag) && is_array($tag)) {
                  foreach ($tag as $value) {
                    $products_tag .= '#<span class="productTag">' . HTML::link(CLICSHOPPING::link(null, 'Search&keywords='. HTML::outputProtected(utf8_decode($value) .'&search_in_description=1&categories_id=&inc_subcat=1'), 'rel="nofollow"'), $value) . '</span> ';
                  }
                }
// display products volume
              $products_volume = $CLICSHOPPING_ProductsFunctionTemplate->getProductsVolume($products_id);
// display products weight
              $products_weight = $CLICSHOPPING_ProductsFunctionTemplate->getProductsWeight($products_id);
// display products lenght
              $products_length = $CLICSHOPPING_ProductsFunctionTemplate->getProductslength($products_id);

// remove product
              $formRemove =  HTML::form('compare', CLICSHOPPING::link(null, 'Compare&Remove' ),'post','class="justify-content-center"', ['tokenize' => true]). "\n";
              $formRemove .= HTML::hiddenField('products_id', $products_id);
              if (isset($_GET['ProductsCompare'])) $formRemove .= HTML::hiddenField('url', 'Compare&ProductsCompare');
              $endform = '</form>';

//******************************************************************************************************************
//            End Options -- activate and insert code in template and css
//******************************************************************************************************************

// **************************
//      Template call
// **************************
              if (is_file($filename)) {
                ob_start();
                require($filename);
                $new_prods_content .= ob_get_clean();
              } else {
                echo CLICSHOPPING::getDef('template_does_not_exist') . '<br /> ' . $filename;
                exit;
              }
            }

            $new_prods_content .= '</div>' . "\n";

          } else {
            $new_prods_content .= '<div class="alert alert-warning text-center">';
            $new_prods_content .= CLICSHOPPING::getDef('text_no_products');
            $new_prods_content .= '</div>';
          }
        } else {
          $new_prods_content = '<div class="alert alert-warning text-center">';
          $new_prods_content .= CLICSHOPPING::getDef('text_no_products');
          $new_prods_content .= '</div>';
        }

        $new_prods_content .= '<!-- New Products End -->' . "\n";

        $CLICSHOPPING_Template->addBlock($new_prods_content, $this->group);
      }
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_PRODUCTS_COMPARE_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want activate this module ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want activate this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select your template ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_TEMPLATE',
          'configuration_value' => 'template_bootstrap_column_5.php',
          'configuration_description' => 'Select your template you want to display',
          'configuration_group_id' => '4',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_multi_template_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please indicate the number of column that you want to display ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_COLUMNS',
          'configuration_value' => '6',
          'configuration_description' => 'Choose a number between 1 and 12',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display a short description ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_SHORT_DESCRIPTION',
          'configuration_value' => '0',
          'configuration_description' => 'Please indicate a number of your short description',
          'configuration_group_id' => '6',
          'sort_order' => '4',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to remove words of your short description ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_SHORT_DESCRIPTION_DELETE_WORLDS',
          'configuration_value' => '0',
          'configuration_description' => 'Veuillez indiquer le nombre de mots à supprimer. Ce système est utitle avec le module des onglets<br><br><i>- 0 pour aucune suppression<br>- 50 pour les 50 premiers caractères</i>',
          'configuration_group_id' => '6',
          'sort_order' => '4',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display a message News / Specials / Favorites / Featured ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_TICKER',
          'configuration_value' => 'False',
          'configuration_description' => 'Display a message News / Specials / Favorites / Featured',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the discount pourcentage (specials) ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_POURCENTAGE_TICKER',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the discount pourcentage (specials)',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please choose the image size',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_IMAGE_MEDIUM',
          'configuration_value' => 'Small',
          'configuration_description' => 'Witch size do you want diplay ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'Small\', \'Medium\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to remove the details button ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DELETE_BUY_BUTTON',
          'configuration_value' => 'False',
          'configuration_description' => 'Remove the button details',
          'configuration_group_id' => '6',
          'sort_order' => '11',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the model ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_MODEL',
          'configuration_value' => 'True',
          'configuration_description' => 'Display the products model ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the weight ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_WEIGHT',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the products weight ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the short description ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_SHORT_DESCRIPTION',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the products short description ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the stock ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_STOCK',
          'configuration_value' => 'none',
          'configuration_description' => 'Display the stock (in stock, exhaused, out of stock) ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'none\', \'image\', \'number\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the brands ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_MANUFACTURER',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the products brands ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the packaging ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_PACKAGING',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the products packaging ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the length ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_LENGTH',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the products length ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to display the quantity minimum order ?',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_DISPLAY_MIN_QTY',
          'configuration_value' => 'False',
          'configuration_description' => 'Display the products quantity minimum order ?',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_PRODUCTS_COMPARE_SORT_ORDER',
          'configuration_value' => '100',
          'configuration_description' => 'Sort order of display. Lowest is displayed first',
          'configuration_group_id' => '6',
          'sort_order' => '12',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array (
        'MODULE_PRODUCTS_COMPARE_STATUS',
        'MODULE_PRODUCTS_COMPARE_TEMPLATE',
        'MODULE_PRODUCTS_COMPARE_COLUMNS',
        'MODULE_PRODUCTS_COMPARE_SHORT_DESCRIPTION',
        'MODULE_PRODUCTS_COMPARE_SHORT_DESCRIPTION_DELETE_WORLDS',
        'MODULE_PRODUCTS_COMPARE_TICKER',
        'MODULE_PRODUCTS_COMPARE_POURCENTAGE_TICKER',
        'MODULE_PRODUCTS_COMPARE_IMAGE_MEDIUM',
        'MODULE_PRODUCTS_COMPARE_DELETE_BUY_BUTTON',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_MODEL',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_WEIGHT',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_STOCK',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_SHORT_DESCRIPTION',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_MANUFACTURER',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_PACKAGING',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_LENGTH',
        'MODULE_PRODUCTS_COMPARE_DISPLAY_MIN_QTY',
        'MODULE_PRODUCTS_COMPARE_SORT_ORDER'
      );
    }
  }
