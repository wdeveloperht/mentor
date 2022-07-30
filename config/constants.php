<?php
define('CONTROLLER_DEFECTO', 'employees');
define('ACTION_DEFECTO', 'index');

if ( !function_exists('pre') ) {
  function pre( $data = FALSE, $e = FALSE ) {
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    print "<pre>";
    print_r($data);
    print "\r\n Called in : " . $caller['file'] . ", At line:" . $caller['line'];
    echo "</pre>\n";
    if ( $e ) {
      exit;
    }
  }
}