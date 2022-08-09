<?php
namespace Controllers;


use Helpers\MainHelper;
use Libraries\MainLibrary;


class Employees Extends MainController  {

	public function __construct() {

	}

  /**
   * Run.
   *
   * @param string $method
   */

  public function init( $method = 'index' ) {
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
    $currentFileName = '';
    $html_employees_list = '';
    $html_employees_scoring_list = '';
    $html_employees_max_average_score = '';


    // get all the CSV files.
    $files = MainLibrary::getAllCSVFiles();

    // get file-name by URL.
    $fileKey = !empty($_GET['file-name']) ? MainHelper::escape_get($_GET['file-name']) : '';

    if ( !empty($fileKey) && !empty($files[$fileKey]) ) {
      $currentFileName = $files[$fileKey];
      // CSV convert to array.
      $employees = MainLibrary::csv_convert_array($files[$fileKey]);

      $mentorEmployeesData = $this->getMentorEmployees([
                                                         'employees' => $employees
                                                       ]);

      $html_employees_list =  MainHelper::html_employees_list( [ 'result' => $employees ] );

      $html_employees_scoring_list = MainHelper::html_employees_scoring_list( [ 'result' => $mentorEmployeesData['mentorEmployees']] );

      $html_employees_max_average_score = MainHelper::html_employees_max_average_score( [ 'result' => $mentorEmployeesData['maxAverageScore']] );
    }

    // load the index view and pass values to it.
    $this->view('employees/index', [
      'currentFileName' => $currentFileName,
      'html_csv_list' => MainHelper::html_csv_list( ['result' => $files] ),
      'html_employees_list' => $html_employees_list,
      'html_employees_scoring_list' => $html_employees_scoring_list,
      'html_employees_max_average_score' => $html_employees_max_average_score
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
      $employees = $args['employees'];
      $mentorEmployees = [];
      $groupsEmployees = [];
      $countEmployees  = count($employees);
      // I count the maximum number of groups․
      // The number of groups is equal to (partnership N to 2) / 2, ( N! / (N-2!) * 2! ) / 2 = (N * N-1) / 4
      $countGroupsEmployees = ( $countEmployees ) * ( $countEmployees - 1 ) / 4;

      // in this case we repeat process until all needed groups will be filled.
      for( $t = 0; $t < $countEmployees; $t++ ) {
        // all employees are compared to each other.
        for ( $i = 0; $i < $countEmployees; $i++ ) {
          for( $j = $i + 1; $j < $countEmployees; $j++) {
            // calculate users score.
            $matchScore = $this->calculateUsersMatchScore($employees[$i], $employees[$j]);
            $need_to_create_group = true;
            foreach( $groupsEmployees as $group_index => $group ) {
              // we exclude that the combination is not repeated, in addition,
              // we also exclude the repetition of individual Employee in the same group
              if ( !in_array( $employees[$i]['Email'] .'-'. $employees[$j]['Email'], $group) && !in_array($employees[$i]['Email'], $group ) && !in_array($employees[$j]['Email'], $group ) ) {
                $groupsEmployees[$group_index][] = $employees[$i]['Email'].'-'.$employees[$j]['Email'];
                $groupsEmployees[$group_index][] = $employees[$i]['Email'];
                $groupsEmployees[$group_index][] = $employees[$j]['Email'];
                $groupsEmployees[$group_index]['memebers'][] = '<b>' . $employees[$i]['Name'] . '</b> will be matched with <b>' . $employees[$j]['Name'] .'</b>';
                $groupsEmployees[$group_index]['score'][] = $matchScore;
                $groupsEmployees[$group_index]['avg_score'] = $this->calculateArrayAvg( $groupsEmployees[$group_index]['score'] );
                $need_to_create_group = false;
              }
            }
            if ( $need_to_create_group === true && count($groupsEmployees) <= $countGroupsEmployees ) {
              $groupsEmployees[] = [
                $employees[$i]['Email'] .'-'. $employees[$j]['Email'],
                $employees[$i]['Email'],
                $employees[$j]['Email'],
                'countMemebers' => $countEmployees,
                'memebers' => ['<b>' . $employees[$i]['Name'] . '</b> will be matched with <b>' . $employees[$j]['Name'] .'</b>'],
                'score' => [ $matchScore ],
                'avg_score' => $this->calculateUsersMatchScore($employees[$i], $employees[$j])
              ];
            }
          }
        }
      }

      $avgScores = [];
      foreach($groupsEmployees as $group_index => $group) {
        $avgScores[$group_index] = $group['avg_score'];
      }

      $maxAVGScores = max($avgScores);

      $avgScoresList = [];
      $maxAverageScore = [];
      if ( !empty($avgScores) ) {
        foreach ( $avgScores as $index => $avgScore ) {
          if ( $avgScore == $maxAVGScores ) {
            $avgScoresList[$index] = $avgScore;
          }
        }
        // get max average score.
        foreach ( $avgScoresList as $index => $val ) {
          $maxAverageScore[] = $groupsEmployees[$index];
        }
      }

      // Scoring of Employees with each other
      for ( $i = 0; $i < $countEmployees; $i++) {
        for( $j = $i + 1; $j < $countEmployees; $j++) {
          $matchScore = $this->calculateUsersMatchScore($employees[$i], $employees[$j]);
          $mentorEmployees[] = [
            'memebers' => '<b>' . $employees[$i]['Name'] . '</b> will be matched with <b>' . $employees[$j]['Name'] . '</b>',
            'score' => $matchScore
          ];
        }
      }

      return [ 'mentorEmployees' => $mentorEmployees, 'maxAverageScore' => $maxAverageScore ];
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
   * Calculate array avg.
   *
   * @param array $args
   *
   * @return float|int
   */
  private function calculateArrayAvg( $args = [] ) {
    $size = count($args);
    if ( $size === 0) {
      return 0;
    }
    $sum = 0;
    foreach( $args as $val ) {
      if ( $val !== -1 ) {
        $sum += $val;
      }
    }

    return $sum / $size;
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
}