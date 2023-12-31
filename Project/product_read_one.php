<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
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
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT p.id, p.image, p.name, p.description, p.price, p.promotion_price, c.category_name, p.manufacture_date, p.expired_date 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id 
            WHERE p.id = ?";

            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $image = $row['image'];
            $name = $row['name'];
            $description = $row['description'];
            $table_price = $row['price'];
            if ($row['promotion_price'] > 0) {
                $table_price = '<span class="text-decoration-line-through">' . number_format($row['price'], 2) . '</span>' . ' ' . number_format($row['promotion_price'], 2);
            }
            $category_name = $row['category_name'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
        }

        /*
        $stmt->bindParam(1, $id);

        The first argument (1 in this case): This specifies the parameter's position in the SQL query. 
        In prepared statements, placeholders are typically represented using question marks (?). 
        The parameter's position is the numerical order in which it appears in the query. 
        In this case, 1 means that the first placeholder in the query will be replaced with the value of the parameter.

        The second argument ($id in this case): This is the value that will be bound to the parameter. 
        The value of the $id variable will be inserted into the prepared statement at the position of the first placeholder (?) 
        when the statement is executed. */

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Image</td>
                <td>
                    <br>
                    <?php
                    echo $image == '' ? "<img src = 'image/image_product.jpg' width = '150' height = '150'>" : "<img src = '$image' width = '150' height = '150'>";
                    ?>

                </td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Price (RM)</td>
                <!-- <td><?php //echo htmlspecialchars($table_price, ENT_QUOTES);  
                            ?></td> -->
                <td><?php echo $table_price;  ?></td>
            </tr>
            <tr>
                <td>Category</td>
                <td><?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td><?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td><?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='product_update.php?id=$id' class='btn btn-primary' style='margin-right: 0.5em;'>Edit</a>";
                    echo "<a href='#' onclick='delete_product({$id});' class='btn btn-danger'>Delete</a>";
                    ?>
                </td>
            </tr>
        </table>
        <a href='product_read.php' class='btn btn-outline-dark'>Back to read products</a>

        <!--
            The ENT_QUOTES flag, when used with the htmlspecialchars() or htmlentities() function, 
            indicates that both single and double quotes should be converted to their respective HTML entities. 
            Specifically, it converts:

            Single quote (') to &#039; or &apos;
            Double quote (") to &quot;
        -->


        <!--
            try: The try block is used to enclose a block of code that might throw an exception or cause an error. 
            When an exception or error occurs within the try block, 
            PHP will stop executing the code within the block and immediately jump to the corresponding catch block.

            catch: The catch block is used to handle exceptions or errors that were thrown within the try block. 
            It allows you to gracefully handle exceptional situations, such as database connection failures, 
            file not found errors, or other exceptional conditions. 
            When an exception occurs, PHP will look for the appropriate catch block that can handle the specific type 
            of exception and execute the code within that block.

            die: die is commonly used for early termination of a script when a specific condition is met or 
            when an error occurs and no further execution is possible or needed. 
            The die function outputs a message and terminates the script immediately.
        -->

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_product(id) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?id=' + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>