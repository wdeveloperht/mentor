<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Task | MentorcliQ</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
      <?php if ( empty($pageData['currentFileName']) ) { ?>
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
        <?php echo $pageData['html_csv_list']; ?>
      </div>
      <?php } ?>
      <?php if ( !empty($pageData['currentFileName']) && !empty($pageData['html_employees_list']) ) { ?>
      <div class="col-lg-12">
          <div class="text-left">
            <a href="/">Back to CSV list</a>
          </div>

          <div class="col-lg-4">
              <h4>Loaded employee list from CSV file <b><?php echo $pageData['currentFileName']; ?></b>!</h4>
              <br>
              <?php echo $pageData['html_employees_list']; ?>
          </div>

          <div class="col-lg-4">
              <h4>Scoring of Employees with each other</h4>
              <br>
              <?php echo $pageData['html_employees_scoring_list']; ?>
          </div>

          <div class="col-lg-4">
            <h4>All employee groups with the highest average score</h4>
            <br>
            <?php echo $pageData['html_employees_max_average_score']; ?>
          </div>
        </div>
      <?php } ?>
    </body>
</html>