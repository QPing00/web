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
            <h1>Read Customers</h1>
        </div>


        <!-- PHP code to read records will be here -->

        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT customer_id, username, first_name, last_name, gender, date_of_birth, registration_date_and_time, account_status FROM customers ORDER BY customer_id ASC";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create New Customer</a>";

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here >

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Username</th>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Gender</th>";
            echo "<th>Date of Birth</th>";
            echo "<th>Registration Date and Time</th>";
            echo "<th>Account Status</th>";
            echo "</tr>";


            // table body will be here

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$customer_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$first_name}</td>";
                echo "<td>{$last_name}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$date_of_birth}</td>";
                echo "<td>{$registration_date_and_time}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?id={$customer_id}' class='btn btn-info' style='margin-right: 1em;'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update.php?id={$customer_id}' class='btn btn-primary' style='margin-right: 1em;'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$customer_id});'  class='btn btn-danger'>Delete</a>";
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