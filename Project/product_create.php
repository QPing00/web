<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Product Create</title>
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
            <h1>Create Product</h1>
        </div>

        <!-- html form to create product will be here -->
        <?php
        $category = "";
        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotion_priceEr = $manufacture_dateEr = $expired_dateEr = "";

        include 'config/database.php';

        if ($_POST) {

            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, category_id=:category_id, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $name = strip_tags($_POST['name']);
                $category = strip_tags($_POST['category']);
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = strip_tags($_POST['manufacture_date']);
                $expired_date = strip_tags($_POST['expired_date']);

                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':category_id', $category);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                $flag = true;

                // Execute the query
                if (empty($name)) {
                    $nameEr = "Please enter the product name";
                    $flag = false;
                }

                if (!is_numeric($category[0])) {
                    $categoryEr = "Please select a category";
                    $flag = false;
                }

                if (empty($description)) {
                    $descriptionEr = "Please enter the product description";
                    $flag = false;
                }

                if (empty($price)) {
                    $priceEr = "Please enter the product price";
                    $flag = false;
                } else if (!is_numeric($price)) {
                    $priceEr = "Product price must be a number";
                    $flag = false;
                }

                if (!empty($promotion_price) && !is_numeric($promotion_price)) {
                    $promotion_priceEr = "Product price must be a number";
                    $flag = false;
                }

                if (empty($manufacture_date)) {
                    $manufacture_dateEr = "Please enter the product manufacture date";
                    $flag = false;
                }

                if (empty($expired_date)) {
                    $expired_dateEr = "Please enter the product expired date";
                    $flag = false;
                }

                if (!empty($promotion_price) && $promotion_price >= $price) {
                    $promotion_priceEr = "Promotion price must be cheaper than original price";
                    $flag = false;
                }

                if (strtotime($manufacture_date) >= strtotime($expired_date)) {
                    $expired_dateEr = "Expired date must be earlier than manufacture date";
                    $flag = false;
                }

                if ($flag == true) {
                    if ($stmt->execute()) {
                        // if $stmt->execute() == true - not problem with above sql 
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $name = $category = $description = $price = $promotion_price = $manufacture_date = $expired_date = "";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
            }
            // show error
            // problem with sql (eg. wrong database link, incorrect username or password, table/column not found, etc)
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' value="<?php echo isset($name) ? $name : ''; ?>" />
                        <div class='text-danger'><?php echo $nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Categories</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="category" id="category">
                            <option>Select a Category</option>
                            <?php
                            $query = "SELECT category_id, category_name FROM categories";
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = ($category_id == $category) ? "selected" : "";
                                echo "<option value='$category_id' $selected>$category_id $category_name</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'><?php echo $categoryEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo isset($description) ? $description : ''; ?></textarea>
                        <div class='text-danger'><?php echo $descriptionEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' value="<?php echo isset($price) ? $price : ''; ?>" />
                        <div class='text-danger'><?php echo $priceEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' value="<?php echo isset($promotion_price) ? $promotion_price : ''; ?>" />
                        <div class='text-danger'><?php echo $promotion_priceEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' value="<?php echo isset($manufacture_date) ? $manufacture_date : ''; ?>" />
                        <div class='text-danger'><?php echo $manufacture_dateEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control' value="<?php echo isset($expired_date) ? $expired_date : ''; ?>" />
                        <div class='text-danger'><?php echo $expired_dateEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>