<?php
// include database connection
include 'session.php';
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $product_exist_query = "SELECT * FROM order_details WHERE product_id = :id";
    $product_exist_stmt = $con->prepare($product_exist_query);
    $product_exist_stmt->bindParam(':id', $id);
    $product_exist_stmt->execute();
    $product_count = $product_exist_stmt->rowCount();

    $image_delete_query = "SELECT image FROM products WHERE id = :id";
    $image_delete_stmt = $con->prepare($image_delete_query);
    $image_delete_stmt->bindParam(':id', $id);
    $image_delete_stmt->execute();
    $row = $image_delete_stmt->fetch(PDO::FETCH_ASSOC);

    // delete query
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id);

    if ($product_count == 0) {
        if ($stmt->execute()) {
            if (!empty($row['image'])) {
                if (file_exists("uploads/")) {
                    unlink($row['image']);
                }
            }
            header('Location: product_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    } else {
        header('Location: product_read.php?action=failToDelete');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
