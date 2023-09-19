<?php
include 'session.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
</head>

<body class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">
    <div class="mb-4 fs-4">
        <?php
        // remove all session variables
        session_unset();

        // destroy the session 
        session_destroy();
        ?>
        Account logged out.
    </div>
    <a class="btn btn-primary" href="index.php">Login</a>
</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</html>