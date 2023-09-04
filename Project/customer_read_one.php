<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Read One</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS →-->
</head>

<body>
    <?php
    include 'navigation.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Customer</h1>
        </div>

        <?php
        $username_check = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record username not found.');

        include 'config/database.php';

        try {
            $query = "SELECT image, username, first_name, last_name, gender, date_of_birth, registration_date_and_time, account_status FROM customers WHERE username = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $username_check);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $image = $row['image'];
            $username = $row['username'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $registration_date_and_time = $row['registration_date_and_time'];
            $account_status = $row['account_status'];
        }


        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <table class='table table-hover table-responsive table-bordered'>
            <tr class='text-center'>
                <td rowspan="8">
                    <br>
                    <?php
                    echo $image == '' ? "<img src = 'image/image_customer.jpg' width = '280' height = '280'>" : "<img src = ' $image ' width = '280' height = '280'>";
                    ?>

                </td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Registration Date and Time</td>
                <td><?php echo htmlspecialchars($registration_date_and_time, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td><?php echo htmlspecialchars($account_status, ENT_QUOTES);  ?></td>
            </tr>
        </table>
        <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>