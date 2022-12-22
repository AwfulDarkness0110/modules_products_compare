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

  namespace ClicShopping\Custom\Sites\Shop\Pages\Compare\Actions;

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  class Remove extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function execute()
    {

      $products_id = HTML::sanitize($_POST['products_id']);

      if (is_array($_SESSION['productsCompare']) && isset($products_id)) {
        $remove_array = $_SESSION['productsCompare'];

        foreach ($remove_array as $key => $value) {
          if ($value == $products_id) {
            unset($_SESSION['productsCompare'][$key]);
          }
        }
      }

      CLICSHOPPING::redirect(null, 'Compare&ProductsCompare');
    }
  }
