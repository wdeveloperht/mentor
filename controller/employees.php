<?php

class EmployeesController {

  public function __construct() {

  }

  /**
   * Run.
   *
   * @param string $method
   */
  public function run( $method = 'index' ) {
    if ( method_exists($this, $method) ) {
      $this->$method();
    }
    else {
      $this->index();
    }
  }

  /**
   *
   */
  public function index() {
    // get all the CSV files.
    $files = $this->getAllCSVFiles();
    $currentFileName = '';
    $mentorAverage = 0;
    $employees = [];
    $mentorEmployees = [];
    // some validation checks should be done here (XSS escaping).
    $fileKey = $_GET['file-name'] ?? '';
    if ( !empty($fileKey) && !empty($files[$fileKey]) ) {
      $currentFileName = $files[$fileKey];
      $employees = $this->csv_convert_array($files[$fileKey]);
      $mentorEmployees = $this->getMentorEmployees([
        'employees' => $employees
      ]);
      if ( !empty($mentorEmployees) ) {
        $mentorAverage = array_sum( array_column( $mentorEmployees, 'score') ) / count($mentorEmployees);
      }
    }

    // load the index view and pass values to it.
    $this->view('employees/index', [
      'files' => $files,
      'currentFileName' => $currentFileName,
      'employees' => $employees,
      'mentorAverage' => $mentorAverage,
      'mentorEmployees' => $mentorEmployees
    ]);
  }

  /**
   * get Mentor Employees.
   *
   * @param array $args
   *
   * @return array
   */
  private function getMentorEmployees( $args = [] ) {
    if ( !empty($args['employees']) ) {
      $mentorEmployees = [];
      $employees = $args['employees'];
      for ($i = 0; $i < count($employees); $i++) {
        for ($j = $i + 1;  $j < count($employees); $j++) {
          $score = $this->calculateUsersMatchScore( $employees[$i], $employees[$j] );
          $mentorEmployees[] = [
            'memebers'  => $employees[$i]['Name'] .' - '. $employees[$j]['Name'],
            'score'     => $score
          ];
        }
      }

      return $mentorEmployees;
    }
    return [];
  }

  /**
   * Calculate users match score.
   *
   * @param array $args1
   * @param array $args2
   *
   * @return int
   */
  private function calculateUsersMatchScore( $args1 = [], $args2 = [] ) {
    $score = 0;
    if ( $args1['Division'] === $args2['Division'] ) {
      $score += 30;
    }
    if ( abs($args1['Age'] - $args2['Age']) <= 5 ) {
      $score += 30;
    }
    if ( $args1['Timezone'] === $args2['Timezone'] ) {
      $score += 40;
    }

    return $score;
  }

  /**
   * Get all the CSV files.
   *
   * @return array
   */
  private function getAllCSVFiles() {
    // it is assumed that only csv files are present here․
    // otherwise a different logic should have been used here
    $filesTmp = array_reverse(array_diff(scandir('uploads'), array( '.', '..', 'index.html' )));
    $files = [];
    if ( !empty($filesTmp) ) {
      foreach ( $filesTmp as $file ) {
        $key = md5($file);
        $files[$key] = $file;
      }
    }

    return $files;
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
   * @param string $fileName
   *
   * @return array
   */
  private function csv_convert_array( $fileName = '' ) {
    $filePath = 'uploads/' . $fileName;
    if ( is_file($filePath) ) {
      $result = [];
      $csvRows = array_map('str_getcsv', file($filePath));
      $csvHeader = array_shift($csvRows);
      // map rows and loop through them.
      if ( !empty($csvRows) ) {
        foreach($csvRows as $row) {
          $result[] = array_combine($csvHeader, $row);
        }
      }
      return $result;
    }

    return [];
  }

  /**
   * Create the view that we pass to it with the indicated data.
   *
   * @param string $view
   * @param array  $pageData
   *
   * @return bool
   */
  private function view( $view = '', $pageData = [] ) {
    $page_view = __DIR__ . '/../view/' . $view . '.php';
    if ( !is_file($page_view) ) {
      echo sprintf('The view %s file not exist.', '"<b>' . $view . '</b>"');

      return FALSE;
    }
    require_once $page_view;
  }
}