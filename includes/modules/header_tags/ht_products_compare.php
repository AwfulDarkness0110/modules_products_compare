<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ht_products_compare
  {
    public $code;
    public $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;
    public $pages;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_header_tags_products_compare_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_products_compare_description');

      if (defined('MODULE_HEADER_PRODUCTS_COMPARE_STATUS')) {
        $this->sort_order = MODULE_HEADER_PRODUCTS_COMPARE_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_PRODUCTS_COMPARE_STATUS == 'True');
        $this->pages = MODULE_HEADER_PRODUCTS_COMPARE_DISPLAY_PAGES;
      }
    }

    public function execute()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $link = CLICSHOPPING::link('ext/ajax/products_compare/compare.php');

      $footer = '<!-- products compare Start -->' . "\n";
//display message
      $footer .= '<script> ';
      $footer .= 'function showProductsCompare() { ';
      $footer .= 'document.getElementById(\'compare\').style.display = "block"; ';
      $footer .= '} ' . "\n";
      $footer .= '</script>' . "\n";

      $footer .= '<script>';
      $footer .= '$(function() { ';
      $footer .= '$(\'input[type=checkbox]\').change(function(){ ';
      $footer .= 'var chkArray = []; ';
//put the selected checkboxes values in chkArray[]
      $footer .= '$(\'input[type=checkbox]:checked\').each(function() { ';
      $footer .= 'chkArray.push($(this).val()); ';
      $footer .= '}); ';

//If chkArray is not empty show the <div> and create the list
      $footer .= 'if(chkArray.length !== 0) { ';
      $footer .= '$.ajax({ ';
      $footer .= 'method: \'POST\', ';
      $footer .= 'url : \'' . $link . '\', ';
      $footer .= 'data : {product_id: chkArray}, ';
      $footer .= 'dataType: \'json\', ';
      $footer .= '}); ';
      $footer .= '} ';
      $footer .= '}); ';
      $footer .= '}) ';
      $footer .= '</script>' . "\n";

      $CLICSHOPPING_Template->addBlock($footer, 'footer_scripts');
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return defined('MODULE_HEADER_PRODUCTS_COMPARE_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous activer ce module ?',
          'configuration_key' => 'MODULE_HEADER_PRODUCTS_COMPARE_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module à votre boutique ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_HEADER_PRODUCTS_COMPARE_SORT_ORDER',
          'configuration_value' => '10',
          'configuration_description' => 'Sort order. Lowest is displayed in first',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer ou la boxe doit s\'afficher',
          'configuration_key' => 'MODULE_HEADER_PRODUCTS_COMPARE_DISPLAY_PAGES',
          'configuration_value' => 'all',
          'configuration_description' => 'Sélectionnez les pages o&ugrave; la boxe doit être présente',
          'configuration_group_id' => '6',
          'sort_order' => '7',
          'set_function' => 'clic_cfg_set_select_pages_list',
          'date_added' => 'now()'
        ]
      );

    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_HEADER_PRODUCTS_COMPARE_STATUS',
        'MODULE_HEADER_PRODUCTS_COMPARE_SORT_ORDER',
        'MODULE_HEADER_PRODUCTS_COMPARE_DISPLAY_PAGES'
      );
    }
  }
