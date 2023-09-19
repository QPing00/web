<?php
// include database connection
include 'session.php';
include 'config/database.php';

try {
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] :  die('ERROR: Record Category ID not found.');

    $category_exist_query = "SELECT * FROM products WHERE category_id = :category_id";
    $category_exist_stmt = $con->prepare($category_exist_query);
    $category_exist_stmt->bindParam(':category_id', $category_id);
    $category_exist_stmt->execute();
    $category_count = $category_exist_stmt->rowCount();

    // delete query
    $query = "DELETE FROM categories WHERE category_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $category_id);

    if ($category_count == 0) {
        if ($stmt->execute()) {
            header('Location: category_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    } else {
        header('Location: category_read.php?action=failToDelete');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
