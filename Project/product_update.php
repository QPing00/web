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
    <?php
    include 'navigation.php';
    ?>

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
            $image_db = $row['image'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotion_priceEr = $manufacture_dateEr = $expired_dateEr = "";
        // error message is empty
        $file_upload_error_messages = "";
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
                $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
                $image = strip_tags($image);
                $delete_image_check = isset($_POST['delete_image']) ? $_POST['delete_image'] : "";
                $flag = true;

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
                } elseif (strtotime($manufacture_date_up) > strtotime(date("Y/m/d"))) {
                    $manufacture_dateEr = "Manufacture date must be no later than today's date";
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

                if ($flag && empty($file_upload_error_messages)) {
                    if ($image) {

                        if ($target_file !== $row['image'] && $row['image'] !== '') {
                            unlink($row['image']);
                        }

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

                    if (!empty($delete_image_check) && $delete_image_check == 'delete_image') {
                        if (!empty($row['image'])) {
                            if (file_exists("uploads/")) {
                                unlink($row['image']);
                                $row['image'] = '';
                            } else {
                                echo "<div class='alert alert-danger'>Failed to delete the image.</div>";
                            }
                        }
                    }

                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE products
                  SET name=:name, category_id=:category_id, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, image=:image 
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
                    $image_update = $image == '' ? $row['image'] : $target_file;
                    $stmt->bindParam(':image', $image_update);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $delete_image_check = "";
                        $image_db = $target_file;
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
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
                            $query = "SELECT category_id, category_name FROM categories ORDER BY category_id ASC";
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
                    <td><input type='date' name='expired_date' value="<?php echo isset($expired_date_up) ? $expired_date_up : $expired_date; ?>" class='form-control' />
                        <div class='text-danger'><?php echo $expired_dateEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td>
                        <?php
                        echo $image_db == '' ? "<img src = 'image/image_product.jpg' width = '100' height = '100'>" : "<img src = '$image_db' width = '100' height = '100'>";
                        ?>
                        <br><br>
                        <input type="checkbox" name="delete_image" value="delete_image" <?php echo (!empty($delete_image_check)) ? "checked" : ""; ?>> Delete Photo
                        <br><br>
                        Update Photo: <input type="file" name="image" />
                        <div class='text-danger'><?php echo $file_upload_error_messages; ?></div>
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