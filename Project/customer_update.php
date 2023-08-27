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

        $usernameEr = $emailEr = $password_oldEr = $password_newEr = $password_new_confirmEr = $first_nameEr = $last_nameEr = $genderEr = $date_of_birthEr = $account_statusEr = '';

        if ($_POST) {
            try {
                // posted values
                $username_up = strip_tags($_POST['username']);
                $email_up = strip_tags($_POST['email']);
                $password_old_up = strip_tags($_POST['password_old']);
                $password_new_up = strip_tags($_POST['password_new']);
                $password_new_confirm_up = strip_tags($_POST['password_new_confirm']);
                $first_name_up = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name_up = strip_tags(ucwords(strtolower($_POST['last_name'])));

                $gender_up = isset($_POST['gender']) ? $_POST['gender'] : "";
                $account_status_up = isset($_POST['account_status']) ? $_POST['account_status'] : "";
                $date_of_birth_up = $_POST['date_of_birth'];

                $password_to_db = password_hash($password_new_up, PASSWORD_DEFAULT);

                $flag = true;


                if (empty($username_up)) {
                    $usernameEr = "Please enter the username";
                    $flag = false;
                } else if (strlen($username_up) < 6) {
                    $usernameEr = 'Minimum 6 characters required';
                    $flag = false;
                }

                $query_username = "SELECT * FROM customers WHERE username = :username";
                $stmt_username = $con->prepare($query_username);
                $stmt_username->bindParam(':username', $username_up);
                $stmt_username->execute();
                $num_username = $stmt_username->rowCount();
                if ($num_username > 0) {
                    $row_username = $stmt_username->fetch(PDO::FETCH_ASSOC);
                    if ($row_username['username'] !== $username) {
                        $usernameEr = "Username is already in use";
                        $flag = false;
                    }
                }

                if (empty($email_up)) {
                    $emailEr = 'Please enter an email address';
                } else if (substr_count($email_up, '@') !== 1) {
                    $emailEr = 'Email format incorrect';
                }

                $query_email = "SELECT * FROM customers WHERE email = :email";
                $stmt_email = $con->prepare($query_email);
                $stmt_email->bindParam(':email', $email_up);
                $stmt_email->execute();
                $num_email = $stmt_email->rowCount();
                if ($num_email > 0) {
                    $row_email = $stmt_email->fetch(PDO::FETCH_ASSOC);
                    if ($row_email['email'] !== $email) {
                        $emailEr = "Email is already in use";
                        $flag = false;
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

                if (!empty($password_new_up) && !empty($password_old_up)) {
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
                }

                if (empty($account_status_up)) {
                    $account_statusEr = "Please select your account status";
                    $flag = false;
                }


                if ($flag) {
                    $query = "UPDATE customers
                        SET username=:username, email=:email, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status 
                        WHERE username = :username";

                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username_up);
                    $stmt->bindParam(':email', $email_up);
                    $stmt->bindParam(':password', $password_to_db);
                    $stmt->bindParam(':first_name', $first_name_up);
                    $stmt->bindParam(':last_name', $last_name_up);
                    $stmt->bindParam(':gender', $gender_up);
                    $stmt->bindParam(':date_of_birth', $date_of_birth_up);
                    $stmt->bindParam(':account_status', $account_status_up);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$username_check}"); ?>" method="post">
            <table class='table table-hover table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' value="<?php echo isset($username_up) ? $username_up : $username; ?>" />
                        <div class='text-danger'><?php echo $usernameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td><input type='text' name='email' class='form-control' value="<?php echo isset($email_up) ? $email_up : $email; ?>" />
                        <div class='text-danger'><?php echo $emailEr; ?></div>
                    </td>
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
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>