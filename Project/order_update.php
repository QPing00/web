<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Update Order</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    include 'navigation.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Update Order</h1>
            <br>
        </div>

        <?php
        $order_id_check = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Record order not found.');

        include 'config/database.php';

        $usernameEr = $username_post = "";
        $product_IDEr = $quantityEr = array();


        try {
            $query = "SELECT * FROM order_summary  
            WHERE order_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $order_id_check);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $username_db = $row['username'];

            $detail_query = "SELECT * FROM order_details  
            WHERE order_id=:order_id";
            $detail_stmt = $con->prepare($detail_query);
            $detail_stmt->bindParam(':order_id', $order_id_check);
            $detail_stmt->execute();
            $product_id_db = array();
            $quantity_db = array();
            $numRows = $detail_stmt->rowCount();
            $row_num = isset($_POST['row_num']) ? $_POST['row_num'] : $numRows;

            while ($detail_row = $detail_stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_id_db[] = $detail_row['product_id'];
                $quantity_db[] = $detail_row['quantity'];
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
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


        if (isset($_POST['submit'])) {

            try {
                $flag = true;
                $resultFlag = false;
                $username_post = $_POST['username'];

                if ($username_post == "") {
                    $usernameEr = "Please select customer.";
                    $flag = false;
                }

                $product_ID_post = $_POST["product_ID"];
                $quantity_post = $_POST["quantity"];
                $atLeastOneProduct = false;
                $product_duplicate = array_unique($_POST["product_ID"]);
                for ($k = 0; $k < count($_POST["product_ID"]); $k++) {
                    $product_IDEr[$k] = "";
                    $quantityEr[$k] = "";

                    if (!$atLeastOneProduct && sizeof($product_duplicate) == 1) {
                        $product_IDEr[$k] = "Please select at least one product.";
                        $flag = false;
                    }

                    if ($_POST["product_ID"][$k] == "" && $_POST["quantity"][$k] !== "") {
                        $product_IDEr[$k] = "Please select a product.";
                        $flag = false;
                    }

                    if ($_POST["product_ID"][$k] !== "" && $_POST["quantity"][$k] == "") {
                        $quantityEr[$k] = "Please fill in the quantity.";
                        $flag = false;
                    } else if ($_POST["quantity"][$k] <= 0 && $_POST["quantity"][$k] !== "") {
                        $quantityEr[$k] = "Quantity must be greater than 0.";
                        $flag = false;
                    }
                }


                if ($flag) {

                    $summary_query = "UPDATE order_summary SET username=:username WHERE order_id = :order_id";
                    $summary_stmt = $con->prepare($summary_query);
                    $summary_stmt->bindParam(':username', $username_post);
                    $summary_stmt->bindParam(':order_id', $order_id_check);

                    if ($summary_stmt->execute()) {
                        $delete_query = "DELETE FROM order_details WHERE order_id = :order_id";
                        $delete_stmt = $con->prepare($delete_query);
                        $delete_stmt->bindParam(':order_id', $order_id_check);
                        $delete_stmt->execute();

                        $details_query = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";

                        $selectedProducts = array();
                        for ($m = 0; $m < count($product_ID_post); $m++) {
                            if ($product_ID_post[$m] != "" && $quantity_post[$m] != "") {
                                $product_ID = $product_ID_post[$m];

                                if (!in_array($product_ID, $selectedProducts)) {
                                    $details_stmt = $con->prepare($details_query);
                                    $quantity = $quantity_post[$m];
                                    $details_stmt->bindParam(':order_id', $order_id_check);
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
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?order_id={$order_id_check}"); ?>" method="post" enctype="multipart/form-data">

            <?php $username_set = isset($_POST["username"]) ? $_POST["username"] : $username_db; ?>
            <div class="row align-items-center">
                <div class="col-md-2">
                    <p class="mb-0">Customer:</p>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="username">
                        <option value="">Select username</option>
                        <?php

                        while ($row = $selectCustomer_stmt->fetch(PDO::FETCH_ASSOC)) {

                            extract($row);

                            $selected = ($username == $username_set) ? 'selected' : '';
                            echo "<option value='$username' $selected>$first_name $last_name ($username)</option>";
                        }
                        ?>
                    </select>
                    <div class='text-danger'>
                        <?php echo $usernameEr; ?>
                    </div>
                </div>
            </div>

            <br>

            <div class="row align-items-center">
                <div class="col-md-2">
                    <p class="mb-0">Select Number of Rows:</p>
                </div>
                <div class="col-md-3">
                    <?php //$row_num = isset($_POST['row_num']) ? $_POST['row_num'] : $numRows; 
                    ?>
                    <select class="form-select mt-1" name="row_num">
                        <?php
                        for ($r = 1; $r <= 10; $r++) {
                            $selected = ($r == $row_num) ? 'selected' : '';
                            echo "<option value='$r' $selected>$r</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-2">
                </div>
                <div class="col-md-3">
                    <br>
                    <input type="submit" value="Confirm" name="row_confirm" class="btn btn-warning" />
                </div>
            </div>

            <br><br>

            <table class='table table-hover table-responsive table-bordered'>
                <?php
                for ($i = 0; $i < $row_num; $i++) {
                    $product_ID_post = isset($_POST["product_ID"][$i]) ? $_POST["product_ID"][$i] : (!empty($product_id_db[$i]) ? $product_id_db[$i] : '');
                    $quantity_post = isset($_POST["quantity"][$i]) ? $_POST["quantity"][$i] : (!empty($quantity_db[$i]) ? $quantity_db[$i] : '');
                ?>
                    <tr>
                        <td>Product
                            <?php echo $i + 1 ?>
                        </td>
                        <td>
                            <?php // print_r($product_ID_post); 
                            ?>
                            <select class="form-select" name="product_ID[]">
                                <option value="">Select product</option>
                                <?php

                                for ($j = 0; $j < count($productID_array); $j++) {
                                    $selected = ($productID_array[$j] == $product_ID_post) ? 'selected' : '';

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
                        <td>
                            <input type='number' name='quantity[]' class='form-control' value="<?php echo isset($quantity_post) ? $quantity_post : $quantity_db; ?>" />
                            <div class='text-danger'>
                                <?php if (!empty($quantityEr[$i])) {
                                    echo $quantityEr[$i];
                                } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td></td>
                    <td colspan=3>
                        <input type='submit' value='Submit' name='submit' class='btn btn-primary' />
                        <?php echo "<a href='#' onclick='delete_order({$order_id_check});' class='btn btn-danger'>Delete</a>"; ?>
                    </td>
                </tr>
            </table>

            <br>
            <a href='order_read.php' class='btn btn-outline-dark'>Back to Read Orders</a>

        </form>
    </div> <!-- end container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_order(order_id) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'order_delete.php?order_id=' + order_id;
            }
        }
    </script>

</body>

</html>