<?php
namespace HT_Core;

require_once(DIR .'/helpers/MainHelper.php');
require_once(DIR .'/libraries/MainLibrary.php');
require_once(DIR .'/controllers/MainController.php');

use Controllers;
use Controllers\MainController;
use Helpers\MainHelper;

Final class Core {

  private function __construct() {

  }

  public static function init() {
    $page 	= !empty($_GET['page']) ? MainHelper::escape_get($_GET['page']) : CONTROLLER_DEFECTO;
    $action = !empty($_GET['action']) ? MainHelper::escape_get($_GET['action']) : ACTION_DEFECTO;

    $allowed_pages = ['employees'];

    if ( !empty($page) && in_array($page, $allowed_pages) ) {
      $page = ucfirst($page);
      $page_controller = DIR . 'controllers/' . $page . '.php';
      if ( !is_file($page_controller) ) {
        echo sprintf('The controller %s file not exist.', '"<b>' . $page . '</b>"');
        return FALSE;
      }
      // load page file.
      require_once($page_controller);
      $className = 'Controllers\\' . $page;
      MainController::run( $className, $action );
    }
    else {
      echo 'Error 404: Page not found!';
    }
  }
}
