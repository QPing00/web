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
        // error message is empty
        $file_upload_error_messages = "";

        include 'config/database.php';

        if ($_POST) {

            try {
                // posted values
                $name = strip_tags($_POST['name']);
                $category = strip_tags($_POST['category']);
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = strip_tags($_POST['manufacture_date']);
                $expired_date = strip_tags($_POST['expired_date']);
                $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
                $image = strip_tags($image);
                $target_file = "";

                $flag = true;

                // $_FILES["image"]["name"]: This represents the original name of the uploaded file on the client's computer.
                // $_FILES["image"]["tmp_name"]: This represents the temporary name assigned to the uploaded file on the server. 
                // basename = file type (eg. .jpeg, .php)

                // now, if image is not empty, try to upload the image
                if ($image) {

                    // upload to file to folder
                    $target_directory = "uploads/"; // folder name
                    $target_file = $target_directory . $image; // the final path: folder name . 乱码 
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION); //pathinfo() find out the final extention of the file name

                    // start validating the submitted file
                    // make sure that file is a real image
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check !== false) {
                        // submitted file is an image

                        // make sure certain file types are allowed
                        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($file_type, $allowed_file_types)) {
                            $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                        }

                        // make sure file does not exist in the server
                        // check if a file with the same name as $target_file already exists in the target directory ("uploads/").
                        if (file_exists($target_file)) {
                            $file_upload_error_messages = "<div>Image already exists. Try to change file name.</div>";
                        }

                        // make sure submitted file is not too large, can't be larger than 512KB (524288 Bytes (in binary))
                        if ($_FILES['image']['size'] > (524288)) {
                            $file_upload_error_messages .= "<div>Image must be less than 512 KB in size.</div>";
                        }

                        // check if the image is square
                        $image_width = $check[0];
                        $image_height = $check[1];
                        if ($image_width !== $image_height) {
                            $file_upload_error_messages .= "<div>Image must be in square.</div>";
                        }
                    } else {
                        $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
                    }
                }

                // Execute the query
                if (empty($name)) {
                    $nameEr = "Please enter the product name";
                    $flag = false;
                }

                if (empty($category)) {
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

                if (!empty($promotion_price)) {
                    if (!is_numeric($promotion_price)) {
                        $promotion_priceEr = "Product price must be a number";
                        $flag = false;
                    } else if ($promotion_price >= $price) {
                        $promotion_priceEr = "Promotion price must be cheaper than original price";
                        $flag = false;
                    }
                } else {
                    $promotion_price = 0;
                }

                if (empty($manufacture_date)) {
                    $manufacture_dateEr = "Please enter the product manufacture date";
                    $flag = false;
                } elseif (strtotime($manufacture_date) > strtotime(date("Y/m/d"))) {
                    $manufacture_dateEr = "Manufacture date must be no later than today's date";
                    $flag = false;
                }

                if (empty($expired_date)) {
                    $expired_dateEr = "Please enter the product expired date";
                    $flag = false;
                }


                if (strtotime($manufacture_date) >= strtotime($expired_date)) {
                    $expired_dateEr = "Expired date must be earlier than manufacture date";
                    $flag = false;
                }

                if ($flag && empty($file_upload_error_messages)) {
                    // insert query
                    $query = "INSERT INTO products SET name=:name, category_id=:category_id, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, image=:image, created=:created";
                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':category_id', $category);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expired_date', $expired_date);
                    $stmt->bindParam(':image', $target_file);
                    // specify when this record was inserted to the database
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    if ($stmt->execute()) {
                        // if $stmt->execute() == true - not problem with above sql 
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $name = $category = $description = $price = $promotion_price = $manufacture_date = $expired_date = "";

                        if ($image) {
                            // make sure the 'uploads' folder exists
                            // if not, create it
                            // if the 'uploads/' directory doesn't exist, it will be created by the code, ensuring that it exists before attempt to upload or perform other operations within it.
                            if (!is_dir($target_directory)) {
                                mkdir($target_directory, 0777, true);
                            }

                            // it means there are no errors, so try to upload the file
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                // move_uploaded_file(filename, destination)
                                // it means photo was uploaded
                            } else {
                                echo "<div class='alert alert-danger'>";
                                echo "<div>Unable to upload photo.</div>";
                                echo "<div>Update the record to upload photo.</div>";
                                echo "</div>";
                            }
                        }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' value="<?php echo isset($name) ? $name : ''; ?>" />
                        <div class='text-danger'><?php echo $nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="category" id="category">
                            <option value="">Select a Category</option>
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
                    <td>Photo</td>
                    <td><input type='file' name='image' />
                        <div class='text-danger'><?php echo $file_upload_error_messages; ?></div>
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
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>