<?php
class MainController {

  public function __construct() {

  }

  /**
   * Create the view that we pass to it with the indicated data.
   *
   * @param string $view
   * @param array  $pageData
   *
   * @return bool
   */
  protected function view( $view = '', $pageData = [] ) {
    $page_view = __DIR__ . '/../view/' . $view . '.php';
    if ( !is_file($page_view) ) {
      echo sprintf('The view %s file not exist.', '"<b>' . $view . '</b>"');

      return FALSE;
    }
    require_once $page_view;
  }

  public function __destruct() {

  }
}