<?php
namespace Libraries;

final class MainLibrary {

  /**
   * Get all the CSV files.
   *
   * @return array
   */
  public static function getAllCSVFiles() {
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
   * @param string $fileName
   *
   * @return array
   */
  public static function csv_convert_array( $fileName = '' ) {
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

}