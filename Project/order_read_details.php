<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Order Read One</title>
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
            <h1>Read Order</h1>
            <br>
        </div>

        <?php
        $order_id_check = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Record order not found.');

        include 'config/database.php';

        try {
            $query = "SELECT os.order_id, os.username, c.first_name, c.last_name, os.order_date_time
            FROM order_summary os
            INNER JOIN customers c ON os.username = c.username 
            WHERE os.order_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $order_id_check);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $order_id = $row['order_id'];
            $username = $row['username'];
            $name = $row['first_name'] . ' ' . $row['last_name'];
            $order_date_time = $row['order_date_time'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Order ID</td>
                <td><?php echo htmlspecialchars($order_id, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Order Date Time</td>
                <td><?php echo htmlspecialchars($order_date_time, ENT_QUOTES);  ?></td>
            </tr>
        </table>

        <br>

        <?php
        $details_query = "SELECT od.order_detail_id, od.order_id, od.product_id, od.quantity, p.name, p.price, p.promotion_price
        FROM order_details od
        INNER JOIN products p ON od.product_id = p.id
        WHERE od.order_id = :order_id
        ORDER BY od.order_detail_id ASC";
        $details_stmt = $con->prepare($details_query);
        $details_stmt->bindParam(':order_id', $order_id);
        $details_stmt->execute();
        $num = $details_stmt->rowCount();

        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr class='table-light'>";
            echo "<th>No.</th>";
            echo "<th>Order Detail ID</th>";
            echo "<th>Product</th>";
            echo "<th>Price</th>";
            echo "<th>Quantity</th>";
            echo "<th>Subtotal</th>";
            echo "</tr>";

            $no = 1;
            $total_amount = 0;

            while ($row2 = $details_stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row2);

                $price_table = $promotion_price == 0 ? $price : $promotion_price;
                $subtotal = $price_table * $quantity;

                echo "<tr>";
                echo "<td>$no</td>";
                echo "<td>$order_detail_id</td>";
                echo "<td>$product_id $name</td>";
                echo "<td>RM " . $price_table . "</td>";
                echo "<td>$quantity</td>";
                echo "<td>RM " . number_format($subtotal, 2) . "</td>";
                echo "</tr>";

                $no++;
                $total_amount += $subtotal;
            }
        }
        ?>
        <tr class='table-light'>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>Total Price</b></td>
            <td><b> RM <?php echo number_format($total_amount, 2); ?></b></td>
        </tr>
        </table>

        <a href='order_read.php' class='btn btn-danger'>Back to read order</a>


    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>