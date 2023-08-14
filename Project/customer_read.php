<!-- used for reading records from the database. 
It uses an HTML table to display the data retrieved from the MySQL database. -->

<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
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
            <h1>Read Customers</h1>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="customer_create.php" class="btn btn-primary m-b-1em">Create New Customer</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>


        <?php
        // include database connection
        include 'config/database.php';

        $query = "SELECT username, email, first_name, last_name, gender, date_of_birth, registration_date_and_time, account_status FROM customers 
            ORDER BY username ASC";

        if ($_GET) {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please enter a product keyword</div>";
            }

            $query = "SELECT username, email, first_name, last_name, gender, date_of_birth, registration_date_and_time, account_status FROM customers WHERE 
            username LIKE '%$search%' OR
            first_name LIKE '%$search%' OR
            last_name LIKE '%$search%'
            ORDER BY username ASC";
        }

        // delete message prompt will be here

        // select all data

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
            echo "<th>Username</th>";
            echo "<th>Email</th>";
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
                echo "<td>{$username}</td>";
                echo "<td>{$email}</td>";
                echo "<td>{$first_name}</td>";
                echo "<td>{$last_name}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$date_of_birth}</td>";
                echo "<td>{$registration_date_and_time}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?username={$username}' class='btn btn-info' style='margin-right: 1em;'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update.php?username={$username}' class='btn btn-primary' style='margin-right: 1em;'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$username});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

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