<?php
define('DIR', str_replace('config', '', dirname( __FILE__ )) );
define('CONTROLLER_DEFECTO', 'employees');
define('ACTION_DEFECTO', 'index');


if ( !function_exists('pre') ) {
  function pre( $data = FALSE, $e = FALSE ) {
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    echo '<pre>' . $data ." \r\n Called in : " . $caller['file'] . ", At line: " . $caller['line'] . "</pre>\n";
    if ( $e ) {
      exit;
    }
  }
}