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
    include 'config/database.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>

        <?php
        $product1 = $product2 = $product3 =  "";
        $product1Er = $product2Er = $product3Er = $product1_qtyEr = $product2_qtyEr = $product3_qtyEr = "";

        $username = $_SESSION['username'];

        if ($_POST) {

            try {
                $product1 = $_POST['product1'];
                $product2 = $_POST['product2'];
                $product3 = $_POST['product3'];
                $product1_qty = $_POST['product1_qty'];
                $product2_qty = $_POST['product2_qty'];
                $product3_qty = $_POST['product3_qty'];

                $flag = true;
                $comparison = true;

                // Execute the query
                if (!is_numeric($product1[0])) {
                    $product1Er = "Please select a product";
                    $flag = false;
                    $comparison = false;
                } else if (empty($product1_qty)) {
                    $product1_qtyEr = "Please enter the product quantity";
                    $flag = false;
                } else {
                    $comparison = true;
                }

                if (!is_numeric($product2[0])) {
                    $product2Er = "Please select a product";
                    $flag = false;
                    $comparison = false;
                } else if (empty($product2_qty)) {
                    $product2_qtyEr = "Please enter the product quantity";
                    $flag = false;
                } else {
                    $comparison = true;
                }

                if (!is_numeric($product3[0])) {
                    $product3Er = "Please select a product";
                    $flag = false;
                    $comparison = false;
                } else if (empty($product3_qty)) {
                    $product3_qtyEr = "Please enter the product quantity";
                    $flag = false;
                } else {
                    $comparison = true;
                }

                if ($comparison == true) {
                    if ($product1 == $product2) {
                        $product2Er = "Product duplicated";
                        $flag = false;
                    }

                    if ($product1 == $product3) {
                        $product3Er = "Product duplicated";
                        $flag = false;
                    }

                    if ($product2 == $product3) {
                        $product3Er = "Product duplicated";
                        $flag = false;
                    }
                }

                if ($flag == true) {
                    $query_summary = "INSERT INTO order_summary SET username=:username, order_date_time=:order_date_time";
                    $stmt_summary = $con->prepare($query_summary);
                    $order_date_time = date('Y-m-d H:i:s');
                    $stmt_summary->bindParam(':username', $username);
                    $stmt_summary->bindParam(':order_date_time', $order_date_time);
                    $stmt_summary->execute();

                    // After the order summary insertion
                    // Get the auto-generated order_id from order_summary
                    // Retrieve the last inserted order ID
                    $order_id = $con->lastInsertId();

                    $query_details = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                    $stmt_details = $con->prepare($query_details);

                    $stmt_details->bindParam(':order_id', $order_id);
                    $stmt_details->bindParam(':product_id', $product1);
                    $stmt_details->bindParam(':quantity', $product1_qty);
                    $stmt_details->execute();

                    $stmt_details->bindParam(':order_id', $order_id);
                    $stmt_details->bindParam(':product_id', $product2);
                    $stmt_details->bindParam(':quantity', $product2_qty);
                    $stmt_details->execute();

                    $stmt_details->bindParam(':order_id', $order_id);
                    $stmt_details->bindParam(':product_id', $product3);
                    $stmt_details->bindParam(':quantity', $product3_qty);
                    $stmt_details->execute();

                    echo "<div class='alert alert-success'>Record was saved.</div>";
                    $product1 = $product2 = $product3 = $product1_qty = $product2_qty = $product3_qty = '';
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <br>
            <p class="h5">Username:
                <?php echo $username; ?>
            </p>
            <br>

            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Product 1</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="product1" id="product1">
                            <option>Select a Product</option>
                            <?php
                            $query = "SELECT id, name, price, promotion_price FROM products";
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = ($id == $product1) ? "selected" : "";
                                echo "<option value='$id' $selected>$id $name</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'><?php echo $product1Er; ?></div>
                    </td>
                    <td>Quantity</td>
                    <td><input type='number' name='product1_qty' class='form-control' value="<?php echo isset($product1_qty) ? $product1_qty : ''; ?>" />
                        <div class='text-danger'><?php echo $product1_qtyEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Product 2</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="product2" id="product2">
                            <option>Select a Product</option>
                            <?php
                            $query = "SELECT id, name, price, promotion_price FROM products";
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = ($id == $product2) ? "selected" : "";
                                echo "<option value='$id' $selected>$id $name</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'><?php echo $product2Er; ?></div>
                    </td>
                    <td>Quantity</td>
                    <td><input type='number' name='product2_qty' class='form-control' value="<?php echo isset($product2_qty) ? $product2_qty : ''; ?>" />
                        <div class='text-danger'><?php echo $product2_qtyEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Product 3</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="product3" id="product3">
                            <option>Select a Product</option>
                            <?php
                            $query = "SELECT id, name, price, promotion_price FROM products";
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = ($id == $product3) ? "selected" : "";
                                echo "<option value='$id' $selected>$id $name</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'><?php echo $product3Er; ?></div>
                    </td>
                    <td>Quantity</td>
                    <td><input type='number' name='product3_qty' class='form-control' value="<?php echo isset($product3_qty) ? $product3_qty : ''; ?>" />
                        <div class='text-danger'><?php echo $product3_qtyEr; ?></div>
                    </td>
                </tr>
            </table>

            <input type='submit' value='Confirm' class='btn btn-primary' />
            <!-- <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a> -->


        </form>

    </div>
    <!-- end .container -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>