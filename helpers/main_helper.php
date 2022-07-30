<?php
/**
 * Print csv list.
 *
 * @param array $args
 *
 * @return false|string
 */
if ( !function_exists('html_csv_list') ) {
  function html_csv_list( $args = [] ) {
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
        if ( !empty($args['files']) ) {
          $index = 0;
          foreach ( $args['files'] as $file ) {
            ?>
            <tr>
              <td><?php echo count($args['files']) - $index; ?></td>
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
    echo ob_get_clean();
  }
}
/**
 * Print employees list.
 *
 * @param array $args
 *
 * @return false|string
 */
if ( !function_exists('html_employees_list') ) {
  function html_employees_list(  $args = [] ) {
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
        foreach ( $args['employees'] as $employee ) {
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
    echo ob_get_clean();
  }
}

/**
 * Print employees scoring list.
 *
 * @param array $args
 *
 * @return false|string
 */
if ( !function_exists('html_employees_scoring_list') ) {
  function html_employees_scoring_list(  $args = [] ) {
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
          foreach ( $args['mentorEmployees'] as $memployee ) {
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
    echo ob_get_clean();
  }
}