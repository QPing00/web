<!DOCTYPE html>

  <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>

  <body>

    <div class="container mt-5">

      <h1>What is your date of birth?</h1>

      <div class="row">


        <div class="col-md-2">

          <div class="btn-group">

            <button type="button" class="btn btn-info btn-lg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              Day
            </button>
            
            <ul class="dropdown-menu">

              <?php for ($day = 1; $day <= 31; $day++) { ?>
                <li><a class="dropdown-item" href="#"> <?php echo $day; ?> </a></li>
              <?php } ?>

            </ul>

          </div>
        </div>


        <div class="col-md-2">

          <div class="btn-group">

            <button type="button" class="btn btn-warning btn-lg dropdown-toggle" data-bs-toggle="dropdown"  aria-expanded="false">
              Month
            </button>

            <ul class="dropdown-menu">

              <?php for ($month = 1; $month <= 12; $month++) { ?>
                <li><a class="dropdown-item" href="#"><?php echo $month; ?></a></li>
              <?php } ?>

            </ul>

          </div>
        </div>


        <div class="col-md-2">

          <div class="btn-group">

            <button type="button" class="btn btn-danger btn-lg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              Year
            </button>

            <ul class="dropdown-menu">

              <?php for ($year = 1990; $year <= 2023; $year++) { ?>
                <li><a class="dropdown-item" href="#"><?php echo $year; ?></a></li>
              <?php } ?>

            </ul>

          </div>
        </div>

      </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
