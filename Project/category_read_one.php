<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Category Read One</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS →-->
</head>

<body>
    <?php
    include 'navigation.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
            <br>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Record Category ID not found.');

        include 'config/database.php';

        try {
            // prepare select query
            $query = "SELECT category_id, category_name, description, expired_date 
            FROM categories
            WHERE category_id = ?";

            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $category_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $category_name = $row['category_name'];
            $description = $row['description'];
            $expired_date = $row['expired_date'];
            $expired_message = $expired_date == 'Yes' ? 'Required' : 'Not Required';
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Category Name</td>
                <td><?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td><?php echo htmlspecialchars($expired_message, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='category_update.php?category_id=$category_id' class='btn btn-primary' style='margin-right: 0.5em;'>Edit</a>";
                    echo "<a href='#' onclick='delete_category({$category_id});' class='btn btn-danger'>Delete</a>";
                    ?>
                </td>
            </tr>
        </table>
        <a href='category_read.php' class='btn btn-outline-dark'>Back to read products</a>

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_category(category_id) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'category_delete.php?category_id=' + category_id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>