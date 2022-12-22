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

  class bm_products_compare {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;
    public $pages;

    public function  __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_boxes_products_compare_title');
      $this->description = CLICSHOPPING::getDef('module_boxes_products_compare_description');

      if ( defined('MODULE_BOXES_PRODUCTS_COMPARE_STATUS')) {
        $this->sort_order = MODULE_BOXES_PRODUCTS_COMPARE_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_PRODUCTS_COMPARE_STATUS == 'True');
        $this->pages = MODULE_BOXES_PRODUCTS_COMPARE_DISPLAY_PAGES;

        $this->group = ((MODULE_BOXES_PRODUCTS_COMPARE_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      }
    }

    public function  execute() {
      $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');
      $CLICSHOPPING_Template = Registry::get('Template');
      $CLICSHOPPING_Service = Registry::get('Service');
      $CLICSHOPPING_Banner = Registry::get('Banner');
      $CLICSHOPPING_ProductsFunctionTemplate = Registry::get('ProductsFunctionTemplate');

      if (isset($_SESSION['productsCompare'])) {
        $products_id_array = $_SESSION['productsCompare'];
      } else {
        $products_id_array = null;
      }

      $col = 0;

      $compare_banner = '';

      if (is_array($products_id_array)) {
        if (count($products_id_array) > 0) {
          $array_unique = array_unique($products_id_array);

          if ($CLICSHOPPING_Service->isStarted('Banner')) {
            if ($banner = $CLICSHOPPING_Banner->bannerExists('dynamic',  MODULE_BOXES_PRODUCTS_COMPARE_BANNER_GROUP)) {
              $compare_banner = $CLICSHOPPING_Banner->displayBanner('static', $banner) . '<br /><br />';
            }
          }

          $button_small_view_details = HTML::link(CLICSHOPPING::link(null, 'Compare&ProductsCompare'), CLICSHOPPING::getDef('module_boxes_products_compare_description'));

          $data ='<!-- Boxe ProductsCompare start -->' . "\n";
          $data .= '<section class="boxe_compare" id="boxe_compare">';
          $data .= '<div class="separator"></div>';
          $data .= '<div class="boxeBannerContentsProductsCompare">' . $compare_banner . '</div>';
          $data .= '<div class="card boxeContainerProductsCompare"">';
          $data .= '<div class="card-header boxeHeadingProductsCompare"><span class="card-title boxeTitleProductsCompare">' . HTML::link(CLICSHOPPING::link(null, 'Compare&ProductsCompare'), CLICSHOPPING::getDef('module_boxes_products_compare_box_title')) . '</span></div>';
          $data .= '<div class="card-block boxeContentArroundProductsCompare">';
          $data .= '<div class="separator"></div>';

          foreach ($array_unique as $products_id) {
            $products_name_url = $CLICSHOPPING_ProductsFunctionTemplate->getProductsUrlRewrited()->getProductNameUrl($products_id);
//product name
            $products_name = $CLICSHOPPING_ProductsCommon->getProductsName($products_id);

            $link_products_compare = HTML::link($products_name_url, $products_name);

            ob_start();
            require($CLICSHOPPING_Template->getTemplateModules('/modules_boxes/content/products_compare'));
            $data .= ob_get_clean();

            $col ++;
            if ($col > 0) {
              $col = 0;
            }
          } //end while

          $data .= '</div>';
          $data .= '<div class="separator"></div>';
          $data .= '<div class="text-center">';
          $data .=  $button_small_view_details;
          $data .= '</div>';
          $data .= '<div class="card-footer boxeBottomContentsProductsCompare"></div>';
          $data .= '</div>' . "\n";
          $data .= '</section>' . "\n";
          $data .='<!-- Boxe ProductsCompare end -->' . "\n";

          $CLICSHOPPING_Template->addBlock($data, $this->group);
        }
      }
    }

    public function  isEnabled() {
      return $this->enabled;
    }

    public function  check() {
      return defined('MODULE_BOXES_PRODUCTS_COMPARE_STATUS');
    }

    public function  install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous activer ce module ?',
          'configuration_key' => 'MODULE_BOXES_PRODUCTS_COMPARE_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module à votre boutique ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez choisir l\'emplacement du contenu de la boxe',
          'configuration_key' => 'MODULE_BOXES_PRODUCTS_COMPARE_CONTENT_PLACEMENT',
          'configuration_value' => 'Right Column',
          'configuration_description' => 'Parmi les options qui vous sont proposées , veuillez en choisir une. <strong>Note :</strong><br /><br /><i>- Column right : Colonne de droite<br />- Column left : Colonne de gauche</i>',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'Left Column\', \'Right Column\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer le groupe d\'appartenance de la banniere',
          'configuration_key' => 'MODULE_BOXES_PRODUCTS_COMPARE_BANNER_GROUP',
          'configuration_value' => SITE_THEMA . '_boxe_compare',
          'configuration_description' => 'Veuillez indiquer le groupe d\'appartenance de la bannière<br /><br /><strong>Note :</strong><br /><i>Le groupe sera à indiquer lors de la création de la bannière dans la section Marketing / Gestion des bannières</i>',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_BOXES_PRODUCTS_COMPARE_SORT_ORDER',
          'configuration_value' => '120',
          'configuration_description' => 'Sort order of display. Lowest is displayed first',
          'configuration_group_id' => '6',
          'sort_order' => '4',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer ou la boxe doit s\'afficher',
          'configuration_key' => 'MODULE_BOXES_PRODUCTS_COMPARE_DISPLAY_PAGES',
          'configuration_value' => 'Categories;Products&Description;Products&Favorites;Products&Featured;Products&ProductsNew;Products&Specials;index.php;search&Q;',
          'configuration_description' => 'Sélectionnez les pages où la boxe doit être présente.',
          'configuration_group_id' => '6',
          'sort_order' => '6',
          'set_function' => 'clic_cfg_set_select_pages_list',
          'date_added' => 'now()'
        ]
      );
    }

    public function  remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function  keys() {
      return array('MODULE_BOXES_PRODUCTS_COMPARE_STATUS',
                   'MODULE_BOXES_PRODUCTS_COMPARE_CONTENT_PLACEMENT',
                   'MODULE_BOXES_PRODUCTS_COMPARE_BANNER_GROUP',
                   'MODULE_BOXES_PRODUCTS_COMPARE_SORT_ORDER',
                   'MODULE_BOXES_PRODUCTS_COMPARE_DISPLAY_PAGES');
    }
  }
