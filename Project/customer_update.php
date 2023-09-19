<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Update Customer</title>
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
            <h1>Update Customer</h1>
            <br>
        </div>

        <?php
        $username_check = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record username not found.');

        include 'config/database.php';

        try {
            $query = "SELECT * FROM customers WHERE username = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $username_check);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $image_db = $row['image'];
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $account_status = $row['account_status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        $emailEr = $password_oldEr = $password_newEr = $password_new_confirmEr = $first_nameEr = $last_nameEr = $genderEr = $date_of_birthEr = $account_statusEr = '';
        $file_upload_error_messages = "";

        if ($_POST) {
            try {
                // posted values
                $password_old_up = strip_tags($_POST['password_old']);
                $password_new_up = strip_tags($_POST['password_new']);
                $password_new_confirm_up = strip_tags($_POST['password_new_confirm']);
                $first_name_up = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name_up = strip_tags(ucwords(strtolower($_POST['last_name'])));

                $gender_up = isset($_POST['gender']) ? $_POST['gender'] : "";
                $account_status_up = isset($_POST['account_status']) ? $_POST['account_status'] : "";
                $date_of_birth_up = $_POST['date_of_birth'];

                $password_to_db = password_hash($password_new_up, PASSWORD_DEFAULT);

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

                if (!empty($password_old_up)) {
                    if (empty($password_new_up)) {
                        $password_newEr = "Please enter new password";
                        $flag = false;
                    }

                    if (!password_verify($password_old_up, $password)) {
                        $password_oldEr = "Password incorrect";
                        $flag = false;
                    } elseif ($password_new_up == $password_old_up) {
                        $password_newEr = "New password is same as old password";
                        $flag = false;
                    }
                }

                if (!empty($password_new_up)) {

                    if (empty($password_old_up)) {
                        $password_oldEr = "Please enter old password";
                        $flag = false;
                    }

                    if (strlen($password_new_up) < 6) {
                        $password_newEr = "Minimum 6 characters required";
                        $flag = false;
                    } elseif (empty($password_new_confirm_up)) {
                        $password_new_confirmEr = "Please confirm your password";
                        $flag = false;
                    }
                }

                if (!empty($password_new_confirm_up)) {

                    if (empty($password_old_up)) {
                        $password_oldEr = "Please enter old password";
                        $flag = false;
                    }

                    if (empty($password_new_up)) {
                        $password_newEr = "Please enter new password";
                        $flag = false;
                    }
                }

                if (!empty($password_new_up) && !empty($password_new_confirm_up)) {
                    if ($password_new_up !== $password_new_confirm_up) {
                        $password_new_confirmEr = "Confirm password does not match with your new password";
                        $flag = false;
                    }
                }

                if (empty($first_name_up)) {
                    $first_nameEr = "Please enter your first name";
                    $flag = false;
                }

                if (empty($last_name_up)) {
                    $last_nameEr = "Please enter your last name";
                    $flag = false;
                }

                if (empty($gender_up)) {
                    $genderEr = "Please select a gender";
                    $flag = false;
                }

                if (empty($date_of_birth_up)) {
                    $date_of_birthEr = "Please select your date of birth";
                    $flag = false;
                } elseif (strtotime($date_of_birth_up) > strtotime(date("Y/m/d"))) {
                    $date_of_birthEr = "Birthdate must be no later than today's date";
                    $flag = false;
                }

                if (empty($account_status_up)) {
                    $account_statusEr = "Please select your account status";
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

                    $query = "UPDATE customers
                        SET password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, image=:image
                        WHERE username = :username";

                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password_to_db);
                    $stmt->bindParam(':first_name', $first_name_up);
                    $stmt->bindParam(':last_name', $last_name_up);
                    $stmt->bindParam(':gender', $gender_up);
                    $stmt->bindParam(':date_of_birth', $date_of_birth_up);
                    $stmt->bindParam(':account_status', $account_status_up);
                    $image_update = ($image == '') ? $row['image'] : $target_file;
                    $stmt->bindParam(':image', $image_update);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                        $delete_image_check = "";
                        $image_db = $image_update;
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$username_check}"); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><?php echo $username; ?>
                    </td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td><?php echo $email; ?>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control' value="<?php echo isset($first_name_up) ? $first_name_up : $first_name; ?>" />
                        <div class='text-danger'><?php echo $first_nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' class='form-control' value="<?php echo isset($last_name_up) ? $last_name_up : $last_name; ?>" />
                        <div class='text-danger'><?php echo $last_nameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <?php $gender_check = isset($gender_up) ? $gender_up : $gender; ?>
                        <input type="radio" name="gender" value="Male" <?php echo ($gender_check && $gender_check == 'Male') ? "checked" : ''; ?>> Male
                        <input type="radio" name="gender" value="Female" <?php echo ($gender_check && $gender_check == 'Female') ? "checked" : ''; ?>> Female
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' value="<?php echo isset($date_of_birth_up) ? $date_of_birth_up : $date_of_birth; ?>" />
                        <div class='text-danger'><?php echo $date_of_birthEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <?php $account_status_check = isset($account_status_up) ? $account_status_up : $account_status; ?>
                        <input type="radio" name="account_status" value="Active" <?php echo (isset($account_status_check) && $account_status_check == 'Active') ? "checked" : ''; ?>> Active
                        <input type="radio" name="account_status" value="Inactive" <?php echo (isset($account_status_check) && $account_status_check == 'Inactive') ? "checked" : ''; ?>> Inactive
                        <input type="radio" name="account_status" value="Pending" <?php echo (isset($account_status_check) && $account_status_check == 'Pending') ? "checked" : ''; ?>> Pending

                        <div class='text-danger'><?php echo $account_statusEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td>
                        <?php
                        echo $image_db == '' ? "<img src = 'image/image_customer.jpg' width = '100' height = '100'>" : "<img src = '$image_db' width = '100' height = '100'>";
                        echo '<br>';
                        if (!empty($image_db)) {
                            echo '<input type="checkbox" name="delete_image" value="delete_image"';
                            echo (!empty($delete_image_check)) ? "checked" : "";
                            echo '> Delete Photo';
                        }
                        ?>
                        <br><br>
                        Update Photo: <input type="file" name="image" />
                        <div class='text-danger'><?php echo $file_upload_error_messages; ?></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><b> <br>Change Password: </b></td>
                </tr>
                <tr>
                    <td>Old Password</td>
                    <td><input type='password' name='password_old' class='form-control' value="<?php echo isset($password_old_up) ? $password_old_up : ''; ?>" />
                        <div class='text-danger'><?php echo $password_oldEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='password' name='password_new' class='form-control' value="<?php echo isset($password_new_up) ? $password_new_up : ''; ?>" />
                        <div class='text-danger'><?php echo $password_newEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Confirm New Password</td>
                    <td><input type='password' name='password_new_confirm' class='form-control' value="<?php echo isset($password_new_confirm_up) ? $password_new_confirm_up : ''; ?>" />
                        <div class='text-danger'><?php echo $password_new_confirmEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <?php
                        echo "<a href='#' onclick=\"delete_user('{$username}');\" class='btn btn-danger'>Delete</a>";
                        ?>
                    </td>
                </tr>
            </table>
            <a href='customer_read.php' class='btn btn-outline-dark'>Back to Read Customers</a>
            <br><br>
        </form>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_user(username) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'customer_delete.php?username=' + username;
            }
        }
    </script>

</body>

</html>