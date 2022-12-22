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
  use ClicShopping\OM\CLICSHOPPING;

  define('CLICSHOPPING_BASE_DIR', realpath(__DIR__ . '/../../../includes/ClicShopping/') . '/');

  require_once(CLICSHOPPING_BASE_DIR . 'OM/CLICSHOPPING.php');
  spl_autoload_register('ClicShopping\OM\CLICSHOPPING::autoload');

  CLICSHOPPING::initialize();

  CLICSHOPPING::loadSite('Shop');

  if (is_array($_POST['product_id']) && isset($_POST['product_id'])) {
    if(isset($_SESSION['productsCompare']) && is_array($_SESSION['productsCompare']) && !is_null(is_array($_SESSION['productsCompare']))) {
      $_SESSION['productsCompare'] = array_merge($_SESSION['productsCompare'], HTML::sanitize($_POST['product_id']));
    } else {
      $_SESSION['productsCompare'] = HTML::sanitize($_POST['product_id']);
    }
  } else {
    unset($_SESSION['productsCompare']);
  }