<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Login Page - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>

        <?php

        $username_loginEr = $password_loginEr = "";

        if ($_POST) {
            // include database connection
            include 'config/database.php';

            // posted values
            $username_login = strip_tags($_POST['username_login']);
            $password_login = strip_tags($_POST['password_login']);


            $flag = true;

            if (empty($username_login)) {
                $username_loginEr = "Please enter the username";
                $flag = false;
            }

            if (empty($password_login)) {
                $password_loginEr = "Please enter the password";
                $flag = false;
            }

            if ($flag) {

                try {
                    // Query the database to check if the username exists
                    $query = "SELECT username, password, account_status FROM customers WHERE username = :username";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':username', $username_login);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    //If $user is empty, it means that the username provided by the user during login does not exist in the database
                    // PDO::FETCH_ASSOC tells PDO to return the row as an associative array,
                    // where column names are used as array keys and the corresponding values are the column values.

                    // No matching username found
                    // !$user: $user == empty 
                    if (!$user) {
                        $username_loginEr = 'Invalid Username';
                    } elseif (password_verify($password_login, $user['password'])) {

                        $account_status = $user['account_status'];
                        if ($account_status == 'Inactive') {
                            echo "<div class='alert alert-danger'>Inactive Account</div>";
                        } elseif ($account_status == 'Pending') {
                            echo "<div class='alert alert-danger'>Pending Account</div>";
                        } else {
                            header("Location: dashboard.php");
                            exit();
                        }
                    } else {
                        $password_loginEr = 'Incorrect Password';
                    }

                    //password_verify() is a PHP function used to verify whether a given plain-text password matches a hashed password.
                    //password_verify(The plain-text password entered by the user, The hashed password retrieved from the database)
                }
                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            }
        }

        ?>


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username_login' class='form-control' value="<?php echo isset($username_login) ? $username_login : ''; ?>" />
                        <div class='text-danger'><?php echo $username_loginEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password_login' class='form-control' value="<?php echo isset($password_login) ? $password_login : ''; ?>" />
                        <div class='text-danger'><?php echo $password_loginEr; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Login' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>