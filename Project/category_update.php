<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Update Category</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    include 'navigation.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
            <br>
        </div>

        <?php
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Record Category ID not found.');

        include 'config/database.php';

        try {
            $query = "SELECT * FROM categories  
            WHERE category_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $category_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $category_name_db = $row['category_name'];
            $description_db = $row['description'];
            $expired_date_db = $row['expired_date'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        $category_nameEr = $descriptionEr = "";

        if ($_POST) {
            try {
                $category_name = strip_tags($_POST['category_name']);
                $description = strip_tags($_POST['description']);
                $expired_date = strip_tags($_POST['expired_date']);
                $flag = true;

                if (empty($category_name)) {
                    $category_nameEr = "Please enter the category name";
                    $flag = false;
                }

                $query_category_name = "SELECT * FROM categories WHERE category_name = :category_name";
                $stmt_category_name = $con->prepare($query_category_name);
                $stmt_category_name->bindParam(':category_name', $category_name);
                $stmt_category_name->execute();
                $num_category_name = $stmt_category_name->rowCount();
                if ($num_category_name > 0) {
                    $row_category_name = $stmt_category_name->fetch(PDO::FETCH_ASSOC);
                    if ($row_category_name['category_name'] !== $category_name_db) {
                        $category_nameEr = "Category name is already in use";
                        $flag = false;
                    }
                }

                if (empty($description)) {
                    $descriptionEr = "Please enter the category description";
                    $flag = false;
                }

                if ($flag) {

                    $query = "UPDATE categories
                    SET category_name=:category_name, description=:description, expired_date=:expired_date
                    WHERE category_id = :category_id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':category_id', $category_id);
                    $stmt->bindParam(':category_name', $category_name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':expired_date', $expired_date);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?category_id={$category_id}"); ?>" method="post">
            <table class='table table-hover table-bordered'>
                <tr>
                    <td>Category Name</td>
                    <td><input type='text' name='category_name' value="<?php echo isset($category_name) ? $category_name : $category_name_db;  ?>" class='form-control' />
                        <div class='text-danger'><?php echo $category_nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo isset($description) ? $description : $description_db;  ?></textarea>
                        <div class='text-danger'><?php echo $descriptionEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td>
                        <?php $check = isset($expired_date) ? $expired_date : $expired_date_db; ?>
                        <input type="radio" name="expired_date" value="Yes" <?php echo ($check && $check == 'Yes') ? "checked" : ''; ?>> Required
                        <input type="radio" name="expired_date" value="No" <?php echo ($check && $check == 'No') ? "checked" : ''; ?>> Not Required
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <?php
                        echo "<a href='#' onclick='delete_category({$category_id});' class='btn btn-danger'>Delete</a>";
                        ?>
                    </td>
                </tr>
            </table>
            <a href='category_read.php' class='btn btn-outline-dark'>Back to read categories</a>
        </form>

    </div> <!-- end .container -->

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
</body>

</html>