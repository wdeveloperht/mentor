<?php
// global setting
require_once 'config/constants.php';
require_once 'helpers/main_helper.php';

pageLader();

function pageLader() {	
	// some validation checks should be done here (XSS escaping).
	$page 	= $_GET['page'] ?? CONTROLLER_DEFECTO;
	$action = $_GET['action'] ?? ACTION_DEFECTO;

  $allowed_pages = ['employees'];
  if ( !empty($page) && in_array($page, $allowed_pages) ) {
	  $page_controller = 'controller/' . $page . '.php';
	  if ( !is_file($page_controller) ) {
      echo sprintf('The controller %s file not exist.', '"<b>' . $page . '</b>"');
      return FALSE;
	  }

	  // load page file.
	  require_once($page_controller);
	  $page_class = $page . 'Controller';

	  // checking page class.
	  if ( !class_exists($page_class) ) {
      echo sprintf('The %s class not exist.', '"<b>' . ($page_class) . '</b>"');
      return FALSE;
	  }

	  // load the instance of the corresponding controller.
	  $pageObj = new $page_class();
	  $pageObj->run( $action );
	}
}