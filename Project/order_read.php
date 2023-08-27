<!-- used for reading records from the database. 
It uses an HTML table to display the data retrieved from the MySQL database. -->

<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Order Read</title>
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
            <h1>Order List</h1>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="order_create.php" class="btn btn-primary m-b-1em">Create New Order</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>


        <?php
        // include database connection
        include 'config/database.php';

        $query = "SELECT os.order_id, os.username, c.first_name, c.last_name, os.order_date_time, SUM(p.price*od.quantity) AS total 
        FROM order_summary os
        INNER JOIN order_details od ON os.order_id = od.order_id
        INNER JOIN customers c ON os.username = c.username
        INNER JOIN products p ON od.product_id = p.id
        GROUP BY os.order_id
        ORDER BY os.order_id DESC";

        // By grouping the rows using GROUP BY order_id, you tell the database to group the rows based on the order_id. 
        // The SUM function is then used to calculate the total price for each group, which corresponds to each unique order_id.

        if ($_GET) {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please enter a keyword</div>";
            }

            $query = "SELECT os.order_id, os.username, c.first_name, c.last_name, os.order_date_time, SUM(p.price*od.quantity) AS total 
            FROM order_summary os
            INNER JOIN order_details od ON os.order_id = od.order_id
            INNER JOIN customers c ON os.username = c.username
            INNER JOIN products p ON od.product_id = p.id
            WHERE os.username LIKE '%$search%'
            OR c.first_name LIKE '%$search%'
            OR c.last_name LIKE '%$search%'
            OR os.order_id LIKE '%$search%' 
            GROUP BY os.order_id
            ORDER BY os.order_id DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered'>";

            echo "<tr>";
            echo "<th>Order ID</th>";
            echo "<th>Username</th>";
            echo "<th>Name</th>";
            echo "<th>Order Date Time</th>";
            echo "<th>Total</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$order_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>$first_name $last_name</td>";
                echo "<td>{$order_date_time}</td>";
                echo "<td>RM " . number_format($total, 2) . "</td>";

                echo "<td>";
                echo "<a href='order_read_details.php?order_id={$order_id}' class='btn btn-info' style='margin-right: 1em;'>Read</a>";
                echo "<a href='update.php?order_id={$order_id}' class='btn btn-primary' style='margin-right: 1em;'>Edit</a>";
                echo "<a href='#' onclick='delete_order({$order_id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>



    </div> <!-- end .container -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- confirm delete record will be here -->

</body>

</html>