<?php
// include database connection
include 'session.php';
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] :  die('ERROR: Record Order Id not found.');

    // delete query
    $query = "DELETE FROM order_summary WHERE order_id = :order_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':order_id', $order_id);

    $details_query = "DELE TE FROM order_details WHERE order_id = :order_id";
    $details_stmt = $con->prepare($details_query);
    $details_stmt->bindParam(':order_id', $order_id);


    if ($stmt->execute() && $details_stmt->execute()) {
        header('Location: order_read.php?action=deleted');
    } else {
        die('Unable to delete record.');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
