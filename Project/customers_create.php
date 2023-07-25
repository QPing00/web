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
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customers</h1>
        </div>

        <!-- html form to create product will be here -->
        <?php

        $usernameEr = $passwordEr = $first_nameEr = $last_nameEr = $genderEr = $date_of_birthEr = $account_statusEr = '';


        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, registration_date_and_time=:registration_date_and_time";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $username = strip_tags(ucwords(strtolower($_POST['username'])));
                $password = strip_tags($_POST['password']);
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));

                $gender = $_POST['gender'] ? strip_tags($_POST['gender']) : "";
                $account_status = isset($_POST['account_status']) ? strip_tags($_POST['account_status']) : "";
                $date_of_birth = strip_tags($_POST['date_of_birth']);

                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':date_of_birth', $date_of_birth);
                $stmt->bindParam(':account_status', $account_status);

                // specify when this record was inserted to the database
                $registration_date_and_time = date('Y-m-d H:i:s');
                $stmt->bindParam(':registration_date_and_time', $registration_date_and_time);

                // Execute the query

                if (empty($username)) {
                    $usernameEr = "Please enter the username";
                }

                if (empty($password)) {
                    $passwordEr = "Please enter the password";
                }

                if (empty($first_name)) {
                    $first_nameEr = "Please enter your first name";
                }

                if (empty($last_name)) {
                    $last_nameEr = "Please enter your last name";
                }

                if (empty($gender)) {
                    $genderEr = "Please select a gender";
                }

                if (empty($date_of_birth)) {
                    $date_of_birthEr = "Please select your date of birth";
                }

                if (empty($account_status)) {
                    $account_statusEr = "Please select your account status";
                }

                if ($usernameEr == "" && $passwordEr == "" && $first_nameEr == "" && $last_nameEr == "" && $genderEr == "" && $date_of_birthEr == "" && $account_statusEr == "") {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $username = $password = $first_name = $last_name = $gender = $date_of_birth = $account_status = "";
                    }
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
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' value="<?php echo isset($username) ? $username : ''; ?>" />
                        <div class='text-danger'><?php echo $usernameEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' value="<?php echo isset($password) ? $password : ''; ?>" />
                        <div class='text-danger'><?php echo $passwordEr; ?></div>
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
                        <?php
                        if (isset($gender) && $gender == 'Male') {
                            echo '<input type="radio" name="gender" value="Male" checked> Male   ';
                        } else {
                            echo '<input type="radio" name="gender" value="Male"> Male   ';
                        }

                        if (isset($gender) && $gender == 'Female') {
                            echo '<input type="radio" name="gender" value="Female" checked> Female';
                        } else {
                            echo '<input type="radio" name="gender" value="Female"> Female';
                        }
                        ?>
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
                        <?php
                        if (isset($account_status) && $account_status == 'Active') {
                            echo '<input type="radio" name="account_status" value="Active" checked> Active   ';
                        } else {
                            echo '<input type="radio" name="account_status" value="Active"> Active   ';
                        }

                        if (isset($account_status) && $account_status == 'Inactive') {
                            echo '<input type="radio" name="account_status" value="Inactive" checked> Inactive   ';
                        } else {
                            echo '<input type="radio" name="account_status" value="Inactive"> Inactive   ';
                        }

                        if (isset($account_status) && $account_status == 'Pending') {
                            echo '<input type="radio" name="account_status" value="Pending" checked> Pending   ';
                        } else {
                            echo '<input type="radio" name="account_status" value="Pending"> Pending   ';
                        }
                        ?>
                        <div class='text-danger'><?php echo $account_statusEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>