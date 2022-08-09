<?php
namespace Helpers;

class MainHelper {
  /**
   * Escape GET
   *
   * @param string $text
   *
   * @return string
   */
  public static function escape_get( $text  = '' ) {
    $code_entities_match = [' ','--','&quot;','!','@','#','$','%','^','&','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','*','+','~','`','='];
    $code_entities_replace = ['','','','','','','','','','','','','','','','','','','','','','','',''];
    $text = str_replace($code_entities_match, $code_entities_replace, $text);

    return mb_strtolower(trim($text, '-'), 'UTF-8');
  }

  /**
   * CSV list.
   *
   * @param array $args
   *
   * @return false|string
   */
  public static function html_csv_list( $args = [] ) {
    ob_start();
    ?>
    <table class="table">
      <tr>
        <th>N</th>
        <th>Name</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
      <?php
      if ( !empty($args['result']) ) {
        $index = 0;
        foreach ( $args['result'] as $file ) {
          ?>
          <tr>
            <td><?php echo count($args['result']) - $index; ?></td>
            <td><?php echo $file; ?> </td>
            <td><?php echo date('Y-m-d H:i:s', str_replace('.xls', '', $file)); ?> </td>
            <td><a href="/?page=employees&action=index&file-name=<?php echo md5($file); ?>">view</a></td>
          </tr>
          <?php
          $index++;
        }
      }
      else {
        ?>
        <tr>
          <td colspan="4">Result not found.</td>
        </tr>
        <?php
      }
      ?>
    </table>
    <?php
    return ob_get_clean();
  }

  /**
   * Employees list.
   *
   * @param array $args
   *
   * @return false|string
   */
  public static function html_employees_list(  $args = [] ) {
    ob_start();
    ?>
    <table class="table">
      <tr>
        <th>N</th>
        <th>Name</th>
        <th>Email</th>
        <th>Division</th>
        <th>Age</th>
        <th>Timezone</th>
      </tr>
      <?php
      $index = 1;
      foreach ( $args['result'] as $employee ) {
        ?>
        <tr>
          <td><?php echo $index++; ?></td>
          <td><?php echo $employee['Name']; ?></td>
          <td><?php echo $employee['Email']; ?></td>
          <td><?php echo $employee['Division']; ?></td>
          <td><?php echo $employee['Age']; ?></td>
          <td><?php echo $employee['Timezone']; ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
    <?php
    return ob_get_clean();
  }

  /**
   * Employees scoring list.
   *
   * @param array $args
   *
   * @return false|string
   */
  public static function html_employees_scoring_list(  $args = [] ) {
    ob_start();
    ?>
    <table class="table">
      <tr>
        <th>N</th>
        <th>Memebers</th>
        <th>Score</th>
      </tr>
      <?php
      $index = 1;
      foreach ( $args['result'] as $memployee ) {
        ?>
        <tr>
          <td><?php echo $index++; ?></td>
          <td><?php echo $memployee['memebers']; ?></td>
          <td><?php echo $memployee['score']; ?> %</td>
        </tr>
        <?php
      }
      ?>
    </table>
    <?php
    return ob_get_clean();
  }

  /**
   * Employees max average score.
   *
   * @param array $args
   *
   * @return false|string
   */
  public static function html_employees_max_average_score(  $args = [] ) {
    ob_start();
    ?>
    <table class="table">
      <tr>
        <th>Memebers</th>
        <th>Score</th>
      </tr>
      <?php
        foreach ( $args['result'] as $memebers ) {
        foreach ( $memebers['memebers'] as $memeberIndex => $memeber ) {
          ?>
          <tr>
            <td><?php echo $memeber; ?></td>
            <td><?php echo $memebers['score'][$memeberIndex]; ?> %</td>
          </tr>
          <?php
        } ?>
        <tr>
          <td colspan="2"><p><?php echo sprintf("In the case of <b>%d</b> employees the highest average match score is <b>%d</b>&#37;", $memebers['countMemebers'], $memebers['avg_score'] );?></p></td>
        </tr>
        <?php
      }
      ?>
    </table>

    <?php
    /*
     *
     *
        foreach ( $args['result'] as $memebers ) {
         foreach ( $memebers['memebers'] as $memeberIndex => $memeber ) {
          ?>
            <tr>
              <td><?php echo $memeber; ?></td>
              <td><?php echo $memebers['score'][$memeberIndex]; ?> %</td>
            </tr>
            <?php
          } ?>
          <tr>
            <td colspan="2"><p><?php echo sprintf("In the case of <b>%d</b> employees the highest average match score is <b>%d</b>&#37;", $memebers['countMemebers'], $memebers['avg_score'] );?></p></td>
          </tr>
          <?php
          }


          foreach ( $args['result']['memebers'] as $memeberIndex => $memeber ) {
          ?>
          <tr>
            <td><?php echo $memeber; ?></td>
            <td><?php echo $args['result']['score'][$memeberIndex]; ?> %</td>
          </tr>
          <?php
        }

    <p><?php echo sprintf("In the case of <b>%d</b> employees the highest average match score is <b>%d</b>&#37;", $args['result']['countMemebers'], $args['result']['avg_score'] );?></p>
     * */
    return ob_get_clean();
  }
}