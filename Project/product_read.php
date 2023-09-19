<!-- used for reading records from the database. 
It uses an HTML table to display the data retrieved from the MySQL database. -->

<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
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
            <h1>Read Products</h1>
        </div>

        <?php $searchEr = ""; ?>

        <div class="d-flex justify-content-between mb-4">
            <a href="product_create.php" class="btn btn-primary m-b-1em">Create New Product</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control form-control-sm me-1 searchField" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>

        <br>

        <?php

        // include database connection
        include 'config/database.php';

        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        } else if ($action == 'failToDelete') {
            echo "<div class='alert alert-danger'>Products already in the order list cannot be deleted.</div>";
        }

        $query = "SELECT p.id, p.image, p.name,p.price, p.promotion_price, c.category_name
        FROM products p
        INNER JOIN categories c ON p.category_id = c.category_id
        ORDER BY id DESC";

        if ($_GET && isset($_GET['search'])) {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please enter a product keyword</div>";
            }

            $query = "SELECT p.id, p.image, p.name, p.price, p.promotion_price, c.category_name
            FROM products p
            INNER JOIN categories c ON p.category_id = c.category_id
            WHERE name LIKE '%$search%' 
            OR category_name LIKE '%$search%'
            ORDER BY id DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here >
            echo '<div class="table-responsive">';
            echo "<table class='table table-hover table-bordered'>"; //start table

            //creating our table heading
            echo "<tr class='text-center'>";
            echo "<th>ID</th>";
            echo "<th>Image</th>";
            echo "<th>Name</th>";
            echo "<th>Category Name</th>";
            echo "<th>Product Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";


            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr class='text-center'>";
                echo "<td>{$id}</td>";
                echo "<td>";
                echo $image == '' ? "<img src = 'image/image_product.jpg' width = '100' height = '100'>" : "<img src = '$image' width = '100' height = '100'>";
                echo "</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$category_name}</td>";

                $table_price = 'RM' . number_format($price, 2);
                if ($promotion_price > 0) {
                    $table_price = '<span class="text-decoration-line-through">' . 'RM' . number_format($price, 2) . '</span>' . ' RM' . number_format($promotion_price, 2);
                }
                echo "<td class='text-end'> $table_price </td>";
                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info' style='margin-right: 0.5em;'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary' style='margin-right: 0.5em; margin-top: 0.5em; margin-bottom:0.5em;'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_product({$id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            //The while loop executes a block of code as long as the specified condition is true.
            /* while (condition is true) {
                 code to be executed;
               }

               The fetch() method is a PDO method used to retrieve rows from the result set of the executed query. 
               The method returns an array containing the next row from the result set, or false if there are no more rows to fetch.

               The extract() function in PHP is used to import the array elements from $row into the current symbol table. 
               In this case, it creates variables with the names corresponding to the keys in the $row array. 
               For example, if $row contains ['id' => 1, 'name' => 'Product A', 'description' => 'Some description', 'price' => 20.99], 
               then after the extract() function, you can directly use variables like $id, $name, $description, and $price.
            */

            // end table
            echo "</table>";
            echo '<div>';
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>



    </div> <!-- end .container -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

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

</body>

</html>