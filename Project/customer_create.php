<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
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
            <h1>Create Customers</h1>
        </div>

        <!-- html form to create product will be here -->
        <?php

        $usernameEr = $emailEr = $passwordEr = $confirm_passwordEr = $first_nameEr = $last_nameEr = $genderEr = $date_of_birthEr = $account_statusEr = '';
        $file_upload_error_messages = "";

        // include database connection
        include 'config/database.php';

        if ($_POST) {

            try {
                // posted values
                $username = strip_tags($_POST['username']);
                $email = strip_tags($_POST['email']);
                $password_ori = strip_tags($_POST['password']);
                $confirm_password = strip_tags($_POST['confirm_password']);
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));

                $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
                $account_status = isset($_POST['account_status']) ? $_POST['account_status'] : "";
                $date_of_birth = $_POST['date_of_birth'];

                $password = password_hash($password_ori, PASSWORD_DEFAULT);

                $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
                $image = strip_tags($image);
                $target_file = "";

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

                if (empty($username)) {
                    $usernameEr = "Please enter the username";
                    $flag = false;
                } else if (strlen($username) < 6) {
                    $usernameEr = 'Minimum 6 characters required';
                    $flag = false;
                }

                $query_username = "SELECT * FROM customers WHERE username = :username";
                $stmt_username = $con->prepare($query_username);
                $stmt_username->bindParam(':username', $username);
                $stmt_username->execute();
                $num_username = $stmt_username->rowCount();
                if ($num_username > 0) {
                    $usernameEr = "Username is already in use";
                    $flag = false;
                }

                if (empty($email)) {
                    $emailEr = 'Please enter an email address';
                } else if (substr_count($email, '@') !== 1) {
                    $emailEr = 'Email format incorrect';
                }

                $query_email = "SELECT * FROM customers WHERE email = :email";
                $stmt_email = $con->prepare($query_email);
                $stmt_email->bindParam(':email', $email);
                $stmt_email->execute();
                $num_email = $stmt_email->rowCount();
                if ($num_email > 0) {
                    $emailEr = "Email is already in use";
                    $flag = false;
                }

                if (empty($password_ori)) {
                    $passwordEr = "Please enter the password";
                    $flag = false;
                } else if (strlen($password_ori) < 6) {
                    $passwordEr = 'Minimum 6 characters required';
                    $flag = false;
                }

                if (empty($confirm_password)) {
                    $confirm_passwordEr = "Please confirm your password";
                    $flag = false;
                } else if ($password_ori !== $confirm_password) {
                    $confirm_passwordEr = "Confirm password does not match with your password";
                    $flag = false;
                }

                if (empty($first_name)) {
                    $first_nameEr = "Please enter your first name";
                    $flag = false;
                }

                if (empty($last_name)) {
                    $last_nameEr = "Please enter your last name";
                    $flag = false;
                }

                if (empty($gender)) {
                    $genderEr = "Please select a gender";
                    $flag = false;
                }

                if (empty($date_of_birth)) {
                    $date_of_birthEr = "Please select your date of birth";
                    $flag = false;
                } elseif (strtotime($date_of_birth) > strtotime(date("Y/m/d"))) {
                    $date_of_birthEr = "Birthdate must be no later than today's date";
                    $flag = false;
                }

                if (empty($account_status)) {
                    $account_statusEr = "Please select your account status";
                    $flag = false;
                }

                if ($flag && empty($file_upload_error_messages)) {
                    $query = "INSERT INTO customers SET username=:username, email=:email, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, registration_date_and_time=:registration_date_and_time, account_status=:account_status, image=:image";
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':date_of_birth', $date_of_birth);
                    $stmt->bindParam(':account_status', $account_status);
                    $stmt->bindParam(':image', $target_file);

                    // specify when this record was inserted to the database
                    $registration_date_and_time = date('Y-m-d H:i:s');
                    $stmt->bindParam(':registration_date_and_time', $registration_date_and_time);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $username = $email = $password_ori = $confirm_password = $first_name = $last_name = $gender = $date_of_birth = $account_status = "";
                        if ($image) {
                            // make sure the 'uploads' folder exists
                            // if not, create it
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
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Photo</td>
                    <td><input type='file' name='image' />
                        <div class='text-danger'><?php echo $file_upload_error_messages; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' value="<?php echo isset($username) ? $username : ''; ?>" />
                        <div class='text-danger'><?php echo $usernameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td><input type='text' name='email' class='form-control' value="<?php echo isset($email) ? $email : ''; ?>" />
                        <div class='text-danger'><?php echo $emailEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' value="<?php echo isset($password_ori) ? $password_ori : ''; ?>" />
                        <div class='text-danger'><?php echo $passwordEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirm_password' class='form-control' value="<?php echo isset($confirm_password) ? $confirm_password : ''; ?>" />
                        <div class='text-danger'><?php echo $confirm_passwordEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control' value="<?php echo isset($first_name) ? $first_name : ''; ?>" />
                        <div class='text-danger'><?php echo $first_nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' class='form-control' value="<?php echo isset($last_name) ? $last_name : ''; ?>" />
                        <div class='text-danger'><?php echo $last_nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="Male" <?php echo (isset($gender) && $gender == 'Male') ? "checked" : ''; ?>> Male
                        <input type="radio" name="gender" value="Female" <?php echo (isset($gender) && $gender == 'Female') ? "checked" : ''; ?>> Female
                        <div class='text-danger'><?php echo $genderEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' value="<?php echo isset($date_of_birth) ? $date_of_birth : ''; ?>" />
                        <div class='text-danger'><?php echo $date_of_birthEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <input type="radio" name="account_status" value="Active" <?php echo (isset($account_status) && $account_status == 'Active') ? "checked" : ''; ?>> Active
                        <input type="radio" name="account_status" value="Inactive" <?php echo (isset($account_status) && $account_status == 'Inactive') ? "checked" : ''; ?>> Inactive
                        <input type="radio" name="account_status" value="Pending" <?php echo (isset($account_status) && $account_status == 'Pending') ? "checked" : ''; ?>> Pending

                        <div class='text-danger'><?php echo $account_statusEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>