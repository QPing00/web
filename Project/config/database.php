<?php
// used to connect to the database
$host = "localhost";
$db_name = "LingQP";
$username = "LingQP";
$password = "kVNAy8GK7Lf(l2E9";
date_default_timezone_set('asia/Kuala_Lumpur');
try {
    $con = new
        PDO(
            "mysql:host={$host};dbname={$db_name}",
            $username,
            $password
        );
    // echo "Connected successfully";
}
// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
