<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Task | MentorcliQ</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
      <div class="col-lg-5">
        <br>
        <form action="index.php?controller=employees&action=upload" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="csv_file">Please select CSV file</label>
            <input type="file" name="csv_file" id="csv_file" />
          </div>
          <input type="submit" value="Upload" class="btn btn-primary btn-sm" />
        </form>
        <hr>
        <h3>CSV List</h3>
        <?php html_csv_list( [ 'files' => $pageData['files'] ] ); ?>
      </div>
      <div class="col-lg-7">
        <?php if ( !empty($pageData['currentFileName']) && !empty($pageData['employees']) && !empty($pageData['mentorEmployees']) ) { ?>
          <div class="row">
            <h3 class="col-lg-8">Loaded employee list from CSV file <b><?php echo $pageData['currentFileName']; ?></b>!</h3>
            <a href="/" class="col-lg-3 text-right" style="margin-top: 20px;">Back to CSV list</a>
          </div>
          <?php html_employees_list( [ 'employees' => $pageData['employees'] ] ); ?>
          <br>
          <h3>Employees Scoring Data.</h3>
          <br>
          <p>
          <?php
            echo sprintf('In the case of %s employees the highest average match score is %s', '<b>' .  count($pageData['employees']) . '</b>', '<b>' . number_format($pageData['mentorAverage'], 2, ',', ' ') . '%</b>');
          ?>
          </p>
          <br>
          <?php html_employees_scoring_list( [ 'mentorEmployees' => $pageData['mentorEmployees']] ); ?>
        <?php } ?>
      </div>
    </body>
</html>