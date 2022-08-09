<?php
namespace Controllers;

class MainController {

  protected static $instance = null;

  public static function get_instance() {
    if ( self::$instance === null ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct() {

  }

  /**
   * Run.
   *
   * @param string $method
   */
  public static function run( $class, $method = 'index' ) {
    if ( ! class_exists($class) ) {
      echo sprintf('The %s class not exist.', '"<b>' . ($class) . '</b>"');
      return FALSE;
    }
    $obj = new $class();
    $obj->init( $method );
  }


  /**
   * Upload.
   */
  public function upload() {
    if ( !empty($_FILES) && !empty($_FILES['csv_file']) ) {
      $upload = $this->_do_upload_csv($_FILES['csv_file']);
      if ( !empty($upload['status']) && $upload['status'] == 1 ) {
        header("Location: /?page=employees&action=index");
      }
      else {
        echo 'please select csv file!';
        die;
      }
    }
  }

  /**
   * upload csv.
   *
   * @param array $file
   *
   * @return array
   */
  private function _do_upload_csv( $file = [] ) {
    $upload_path = 'uploads/';
    if ( !is_dir($upload_path) ) {
      @mkdir($upload_path, 0755, TRUE);
      copy($upload_path . '/index.html', $upload_path . '/index.html');
    }
    $tmp_name = $file['name'];
    $tmp = $file['tmp_name'];
    $ex = explode('.', $tmp_name);
    $csv_mimetypes = array(
      'text/csv',
      'text/plain',
      'application/csv',
      'text/comma-separated-values',
      'application/excel',
      'application/vnd.ms-excel',
      'application/vnd.msexcel',
      'text/anytext',
      'application/octet-stream',
      'application/txt',
    );
    if ( in_array($file['type'], $csv_mimetypes) ) {
      $name = time() . '.' . end($ex);
      $uploaded = move_uploaded_file($tmp, $upload_path . $name);
      $status = 0;
      $message = 'field upload!';
      if ( $uploaded ) {
        $status = 1;
        $message = 'success';
      }

      return [
        'status' => $status,
        'message' => $message,
        'name' => $name,
      ];
    }

    return [
      'status' => 0,
      'message' => 'error!',
    ];
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
    $page_view = DIR . '/view/' . $view . '.php';
    if ( !is_file($page_view) ) {
      echo sprintf('The view %s file not exist.', '"<b>' . $view . '</b>"');

      return FALSE;
    }
    require_once $page_view;
  }

  public function __destruct() {

  }
}