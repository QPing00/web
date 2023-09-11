<?php
// include database connection
include 'session.php';
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $username = isset($_GET['username']) ? $_GET['username'] :  die('ERROR: Record username not found.');

    $customer_exist_query = "SELECT * FROM order_summary WHERE username = :username";
    $customer_exist_stmt = $con->prepare($customer_exist_query);
    $customer_exist_stmt->bindParam(':username', $username);
    $customer_exist_stmt->execute();
    $customer_count = $customer_exist_stmt->rowCount();

    $image_delete_query = "SELECT image FROM customers WHERE username = :username";
    $image_delete_stmt = $con->prepare($image_delete_query);
    $image_delete_stmt->bindParam(':username', $username);
    $image_delete_stmt->execute();
    $row = $image_delete_stmt->fetch(PDO::FETCH_ASSOC);

    // delete query
    $query = "DELETE FROM customers WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $username);

    if ($customer_count == 0) {
        if ($stmt->execute()) {
            if (!empty($row['image'])) {
                if (file_exists("uploads/")) {
                    unlink($row['image']);
                }
            }
            header('Location: customer_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    } else {
        header('Location: customer_read.php?action=failToDelete');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
