<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Create Categories</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
</head>

<body>
    <?php
    include 'navigation.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Create Category</h1>
        </div>

        <?php

        $category_nameEr = $descriptionEr = "";

        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO categories SET category_name=:category_name, description=:description, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $category_name = strip_tags($_POST['category_name']);
                $description = strip_tags($_POST['description']);

                // bind the parameters
                $stmt->bindParam(':category_name', $category_name);
                $stmt->bindParam(':description', $description);

                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);


                // Execute the query

                $flag = true;

                if (empty($category_name)) {
                    $category_nameEr = "Please enter the category name";
                    $flag = false;
                }

                if (empty($description)) {
                    $descriptionEr = "Please enter the category description";
                    $flag = false;
                }

                if ($flag == true) {
                    if ($stmt->execute()) {
                        // if $stmt->execute() == true - not problem with above sql 
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $category_name = $description = "";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
            }
            // show error
            // problem with sql (eg. wrong database link, incorrect username or password, table/column not found, etc)
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category Name</td>
                    <td><input type='text' name='category_name' class='form-control' value="<?php echo isset($category_name) ? $category_name : ''; ?>" />
                        <div class='text-danger'><?php echo $category_nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo isset($description) ? $description : ''; ?></textarea>
                        <div class='text-danger'><?php echo $descriptionEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <!-- <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a> -->
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>