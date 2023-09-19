<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Dashboard - PHP CRUD Tutorial</title>
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
            <h1>Dashboard</h1>
            <br>
        </div>

        <?php
        include 'config/database.php';

        $total_product_query = "SELECT COUNT(id) FROM products";
        $total_product_stmt = $con->prepare($total_product_query);
        $total_product_stmt->execute();
        $total_product_row = $total_product_stmt->fetch(PDO::FETCH_ASSOC);
        $total_product_count = $total_product_row['COUNT(id)'];

        $total_cus_query = "SELECT COUNT(username) FROM customers";
        $total_cus_stmt = $con->prepare($total_cus_query);
        $total_cus_stmt->execute();
        $total_cus_row = $total_cus_stmt->fetch(PDO::FETCH_ASSOC);
        $total_cus_count = $total_cus_row['COUNT(username)'];

        $total_order_query = "SELECT COUNT(order_id) FROM order_summary";
        $total_order_stmt = $con->prepare($total_order_query);
        $total_order_stmt->execute();
        $total_order_row = $total_order_stmt->fetch(PDO::FETCH_ASSOC);
        $total_order_count = $total_order_row['COUNT(order_id)'];

        $total_cat_query = "SELECT COUNT(category_id) FROM categories";
        $total_cat_stmt = $con->prepare($total_cat_query);
        $total_cat_stmt->execute();
        $total_cat_row = $total_cat_stmt->fetch(PDO::FETCH_ASSOC);
        $total_cat_count = $total_cat_row['COUNT(category_id)'];

        $revenue_query = "SELECT SUM(
            CASE
                WHEN p.promotion_price > 0 THEN (od.quantity * p.promotion_price)
                ELSE (od.quantity * p.price)
            END
        ) AS total_revenue
        FROM order_details od
        INNER JOIN products p ON od.product_id = p.id";
        $revenue_stmt = $con->prepare($revenue_query);
        $revenue_stmt->execute();
        $revenue_total_row = $revenue_stmt->fetch(PDO::FETCH_ASSOC);
        $revenue_total = $revenue_total_row['total_revenue'];



        ?>

        <div class="card-group text-center">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-text">Total Products</h6>
                    <h3 class="card-text"><?php echo $total_product_count ?></h3>
                </div>
            </div>
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-text">Total Categories</h6>
                    <h3 class="card-text"><?php echo $total_cat_count ?></h3>
                </div>
            </div>
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Customers</h6>
                    <h3 class="card-text"><?php echo $total_cus_count ?></h3>
                </div>
            </div>
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Orders</h6>
                    <h3 class="card-text"><?php echo $total_order_count ?></h3>
                </div>
            </div>
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Revenue</h6>
                    <h3 class="card-text">RM <?php echo number_format($revenue_total, 2) ?></h3>
                </div>
            </div>
        </div>

        <br><br>

        <?php
        $top_product_query = "SELECT p.name, SUM(od.quantity) AS total_quantity
        FROM order_details od
        INNER JOIN products p ON od.product_id = p.id
        GROUP BY od.product_id
        ORDER BY total_quantity DESC
        LIMIT 5";
        $top_product_stmt = $con->prepare($top_product_query);
        $top_product_stmt->execute();

        $least_product_query = "SELECT p.name, SUM(od.quantity) AS total_quantity
            FROM order_details od
            INNER JOIN products p ON od.product_id = p.id
            GROUP BY od.product_id
            ORDER BY total_quantity ASC
            LIMIT 5";
        $least_product_stmt = $con->prepare($least_product_query);
        $least_product_stmt->execute();



        $top_cus_query = "SELECT username, COUNT(order_id) AS total_orders
            FROM order_summary
            GROUP BY username
            ORDER BY total_orders DESC
            LIMIT 5";
        $top_cus_stmt = $con->prepare($top_cus_query);
        $top_cus_stmt->execute();
        ?>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 Best Selling Products</h5>
                        <ul class="list-group list-group-flush">
                            <?php
                            $counter = 1;
                            while ($row = $top_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<li class='list-group-item'>$counter.  $name</li>";
                                $counter++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 Least Selling Products</h5>
                        <ul class="list-group list-group-flush">
                            <?php
                            $counter = 1;
                            while ($row = $least_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<li class='list-group-item'>$counter.  $name</li>";
                                $counter++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 Supportive Customers</h5>
                        <ul class="list-group list-group-flush">
                            <?php
                            $counter = 1;
                            while ($row = $top_cus_stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<li class='list-group-item'>$counter.  $username</li>";
                                $counter++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>




    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>