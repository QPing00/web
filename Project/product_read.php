<!-- used for reading records from the database. 
It uses an HTML table to display the data retrieved from the MySQL database. -->

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
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Products</h1>
        </div>

        <?php $searchEr = ""; ?>

        <a href='product_create.php' class='btn btn-primary m-b-1em'>Create New Product</a>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <div class="d-flex justify-content-end">
                <input type="search" id="search" name="search" />
                <input type="submit" value="Search" class="btn btn-secondary" />

            </div>
        </form>


        <?php

        // include database connection
        include 'config/database.php';

        $query = "SELECT id, name, category_id, description, price FROM products ORDER BY id ASC";

        if ($_GET) {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please enter a product keyword</div>";
            }

            $query = "SELECT id, name, category_id, description, price FROM products WHERE 
            name LIKE '%$search%'
            ORDER BY id ASC";
        }

        // delete message prompt will be here


        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();


        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here >
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Category ID</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";


            // table body will be here

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$category_id}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info' style='margin-right: 1em;'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update.php?id={$id}' class='btn btn-primary' style='margin-right: 1em;'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$id});'  class='btn btn-danger'>Delete</a>";
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
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>



    </div> <!-- end .container -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- confirm delete record will be here -->

</body>

</html>