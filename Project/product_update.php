<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Update Product</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- custom css -->
    <!-- <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style> -->
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
            <br>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM products  
            WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $name = $row['name'];
            $category_id_db = $row['category_id'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotion_priceEr = $manufacture_dateEr = $expired_dateEr = "";

        if ($_POST) {
            try {
                // posted values
                $name_up = strip_tags($_POST['name']);
                $category_up = strip_tags($_POST['category']);
                $description_up = strip_tags($_POST['description']);
                $price_up = strip_tags($_POST['price']);
                $promotion_price_up = strip_tags($_POST['promotion_price']);
                $manufacture_date_up = strip_tags($_POST['manufacture_date']);
                $expired_date_up = strip_tags($_POST['expired_date']);

                $flag = true;

                if (empty($name_up)) {
                    $nameEr = "Please enter the product name";
                    $flag = false;
                }

                if (empty($category_up)) {
                    $categoryEr = "Please select a category";
                    $flag = false;
                }

                if (empty($description_up)) {
                    $descriptionEr = "Please enter the product description";
                    $flag = false;
                }

                if (empty($price_up)) {
                    $priceEr = "Please enter the product price";
                    $flag = false;
                } else if (!is_numeric($price_up)) {
                    $priceEr = "Product price must be a number";
                    $flag = false;
                }

                if (!empty($promotion_price_up)) {
                    if (!is_numeric($promotion_price_up)) {
                        $promotion_priceEr = "Product price must be a number";
                        $flag = false;
                    } else if ($promotion_price_up >= $price_up) {
                        $promotion_priceEr = "Promotion price must be cheaper than original price";
                        $flag = false;
                    }
                } else {
                    $promotion_price_up = 0;
                }

                if (empty($manufacture_date_up)) {
                    $manufacture_dateEr = "Please enter the product manufacture date";
                    $flag = false;
                }

                if (empty($expired_date_up)) {
                    $expired_dateEr = "Please enter the product expired date";
                    $flag = false;
                }

                if (strtotime($manufacture_date_up) >= strtotime($expired_date_up)) {
                    $expired_dateEr = "Expired date must be earlier than manufacture date";
                    $flag = false;
                }

                if ($flag) {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE products
                  SET name=:name, category_id=:category_id, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date 
                  WHERE id = :id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':name', $name_up);
                    $stmt->bindParam(':category_id', $category_up);
                    $stmt->bindParam(':description', $description_up);
                    $stmt->bindParam(':price', $price_up);
                    $stmt->bindParam(':promotion_price', $promotion_price_up);
                    $stmt->bindParam(':manufacture_date', $manufacture_date_up);
                    $stmt->bindParam(':expired_date', $expired_date_up);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo isset($name_up) ? $name_up : $name;  ?>" class='form-control' />
                        <div class='text-danger'><?php echo $nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-control" aria-label="Default select example" name="category">
                            <option value="">Select a Category</option>
                            <?php
                            $query = "SELECT category_id, category_name FROM categories";
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = ($category_id == (isset($category_up) ? $category_up : $category_id_db)) ? "selected" : "";
                                echo "<option value='$category_id' $selected>$category_id $category_name</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'><?php echo $categoryEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo isset($description_up) ? $description_up : $description;  ?></textarea>
                        <div class='text-danger'><?php echo $descriptionEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' value="<?php echo isset($price_up) ? $price_up : $price;  ?>" class='form-control' />
                        <div class='text-danger'><?php echo $priceEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' value="<?php echo isset($promotion_price_up) ? $promotion_price_up : $promotion_price;  ?>" class='form-control' />
                        <div class='text-danger'><?php echo $promotion_priceEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' value="<?php echo isset($manufacture_date_up) ? $manufacture_date_up : $manufacture_date; ?>" class='form-control' />
                        <div class='text-danger'><?php echo $manufacture_dateEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' value="<?php echo isset($expired_date_up) ? $expired_date_up : $expired_date;  ?>" class='form-control' />
                        <div class='text-danger'><?php echo $expired_dateEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>