<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
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
            <h1>Create Order</h1>
        </div>

        <?php

        include 'config/database.php';
        date_default_timezone_set('asia/Kuala_Lumpur');

        $usernameEr = $username_post = "";
        $product_IDEr = $quantityEr = array();

        if ($_POST && isset($_POST['save'])) {

            try {
                $summary_query = "INSERT INTO order_summary SET username=:username, order_date_time=:order_date_time";
                $summary_stmt = $con->prepare($summary_query);
                $username_post = $_POST['username'];
                $summary_stmt->bindParam(':username', $username_post);
                $order_date_time = date('Y-m-d H:i:s');
                $summary_stmt->bindParam(':order_date_time', $order_date_time);

                $flag = true;
                $resultFlag = false;

                if ($username_post == "") {
                    $usernameEr = "Please select customer.";
                    $flag = false;
                }

                $product_ID_post = $_POST["product_ID"];
                $quantity_post = $_POST["quantity"];
                /* $product_duplicate = array_unique($_POST["product_ID"]); */

                // Product ID Validation Loop
                // used to validate the selected product IDs and corresponding quantities that the user has submitted in the form
                // iterates through the selected products and quantities and checks for various validation conditions
                // such as whether a product has been selected, whether different products have been selected in the same order, and whether quantities are valid.
                for ($k = 0; $k < count($product_ID_post); $k++) {
                    $product_IDEr[$k] = "";
                    $quantityEr[$k] = "";
                    if ($product_ID_post == "" && $quantity_post !== "") {
                        $product_IDEr[$k] = "Please select a product.";
                        $flag = false;
                    } /*else if (sizeof($product_duplicate) != sizeof($_POST["product_ID"])) {
                        $product_IDEr[$k] = "Please select different product.";
                        $flag = false; 
                    }*/
                    if ($product_ID_post !== "" && $quantity_post == "") {
                        $quantityEr[$k] = "Please fill in the quantity.";
                        $flag = false;
                    } else if ($quantity_post[$k] <= 0) {
                        $quantityEr[$k] = "Quantity must be greater than 0.";
                        $flag = false;
                    }
                }


                if ($flag) {
                    if ($summary_stmt->execute()) {
                        //retrieves ID of the last inserted row into a database with an auto-increment column
                        $order_ID = $con->lastInsertId();

                        $details_query = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";

                        // Summary Query Loop
                        // process the products and quantities that the user has submitted in the form
                        // iterates through each product and quantity submitted and performs database insertions for each of them.
                        // responsible for adding the selected products and quantities to the order_details table.
                        $selectedProducts = array();
                        for ($m = 0; $m < count($product_ID_post); $m++) {
                            if ($product_ID_post[$m] != "" && $quantity_post[$m] != "") {
                                $product_ID = $product_ID_post[$m];

                                if (!in_array($product_ID, $selectedProducts)) {
                                    $details_stmt = $con->prepare($details_query);
                                    $quantity = $quantity_post[$m];
                                    $details_stmt->bindParam(':order_id', $order_ID);
                                    $details_stmt->bindParam(':product_id', $product_ID);
                                    $details_stmt->bindParam(':quantity', $quantity);
                                    $details_stmt->execute();
                                    $resultFlag = true;
                                    $selectedProducts[] = $product_ID;
                                }
                            }
                        }

                        if ($resultFlag) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                            $username_post = "";
                            for ($n = 0; $n < count($product_ID_post); $n++) {
                                $product_ID_post[$n] = $quantity_post[$n] = '';
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        $selectCustomer_query = "SELECT username, first_name, last_name FROM customers ORDER BY username ASC";
        $selectCustomer_stmt = $con->prepare($selectCustomer_query);
        $selectCustomer_stmt->execute();

        $selectProduct_query = "SELECT id, name, price, promotion_price FROM products";
        $selectProduct_stmt = $con->prepare($selectProduct_query);
        $selectProduct_stmt->execute();

        $productID_array = array();
        $productName_array = array();
        $promotionPrice_array = array();
        $price_array = array();

        while ($row = $selectProduct_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($productID_array, $id);
            array_push($productName_array, $name);
            array_push($promotionPrice_array, $promotion_price);
            array_push($price_array, $price);
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="row align-items-center">
                <p class="mb-0">Select Number of Rows:</p>
                <div class="col-md">
                    <select class="form-select mt-1" name="row_num">
                        <?php
                        $row_num = $_POST['row_num'];
                        for ($r = 1; $r <= 10; $r++) {
                            $selected = ($r == $row_num) ? 'selected' : '';
                            echo "<option value='$r' $selected>$r</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md">
                    <input type="submit" value="Confirm" name="row_confirm" class="btn btn-warning btn-block" />
                </div>
            </div>
        </form>

        <br>

        <?php

        if ($_POST && isset($row_num)) {

        ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Customer</td>
                        <td colspan=3>
                            <select class="form-select" name="username">
                                <option value="">Select username</option>
                                <?php

                                while ($row = $selectCustomer_stmt->fetch(PDO::FETCH_ASSOC)) {

                                    extract($row);

                                    $selected = ($username == $username_post) ? 'selected' : '';
                                    echo "<option value='$username' $selected>$first_name $last_name ($username)</option>";
                                }
                                ?>
                            </select>
                            <div class='text-danger'>
                                <?php echo $usernameEr; ?>
                            </div>
                        </td>
                    </tr>

                    <?php
                    // Outer Loop (Form Loop)
                    // generate rows for selecting products and quantities
                    for ($i = 0; $i < $row_num; $i++) {
                    ?>
                        <tr>
                            <td>Product
                                <?php echo $i + 1 ?>
                            </td>
                            <td>
                                <select class="form-select" name="product_ID[]">
                                    <option value="">Select product</option>
                                    <?php

                                    // Inner Loop (Product Selection Loop)
                                    // This loop is inside the product selection column. 
                                    // It iterates through the list of available products ($productID_array) and generates <option> elements within the <select> dropdown. 
                                    // The loop generates as many options as there are products in the array.
                                    for ($j = 0; $j < count($productID_array); $j++) {
                                        $selected = (isset($product_ID_post[$i]) && $productID_array[$j] == $product_ID_post[$i]) ? 'selected' : '';

                                        if ($promotionPrice_array[$j] > 0) {
                                            echo "<option value='$productID_array[$j]' $selected>$productID_array[$j] $productName_array[$j] (RM$price_array[$j] -> RM$promotionPrice_array[$j])</option>";
                                        } else {
                                            echo "<option value='$productID_array[$j]' $selected>$productID_array[$j] $productName_array[$j] (RM$price_array[$j])</option>";
                                        }
                                    }

                                    // to reuse the prepared statement
                                    $selectProduct_stmt->closeCursor();
                                    ?>
                                </select>
                                <div class='text-danger'>
                                    <?php if (!empty($product_IDEr[$i])) {
                                        echo $product_IDEr[$i];
                                    } ?>
                                </div>
                            </td>
                            <td>Quantity</td>
                            <td><input type='number' name='quantity[]' class='form-control' value="<?php echo isset($quantity_post[$i]) ? $quantity_post[$i] : ''; ?>" />
                                <div class='text-danger'>
                                    <?php if (!empty($quantityEr)) {
                                        echo $quantityEr[$i];
                                    } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td></td>
                        <td colspan=3>
                            <input type='submit' value='Save' name='save' class='btn btn-primary' />
                        </td>
                    </tr>
                </table>
            </form>
        <?php } ?>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>