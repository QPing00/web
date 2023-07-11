<html>


<head>

    <style>
        .error {
            color: red;
        }

        .requirement {
            font-size: 12px;
            color: red;
        }
    </style>

</head>


<body>

    <?php

    function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;

        // trim($data): This function removes any leading or trailing whitespace from the input data.
        // stripslashes($data): This function removes backslashes (\) from the input data. 
        // htmlspecialchars($data): This function converts special characters to their corresponding HTML entities. 
    }


    $f_nameEr = $l_nameEr = $bdayEr = $genEr = $u_nameEr = $pswordEr = $con_pswordEr = $emailEr = " ";
    $f_name = $l_name = $gender = $u_name = $psword = $con_psword = $email = $bdate = "";


    if ($_POST) {

        $f_name = $_POST['first_name'];
        $l_name = $_POST['last_name'];
        $day = $_POST['day'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        $gender = $_POST['gender'];
        $u_name = $_POST["username"];
        $psword = $_POST["password"];
        $con_psword = $_POST["confirm_password"];
        $email = $_POST["email"];


        $f_name = ucwords(strtolower(input($f_name)));
        $l_name = ucwords(strtolower(input($l_name)));


        $bdate = $day . "-" . $month . "-" . $year;


        if (isset($gender)) {
            $gender;
        }

        $u_name = input($u_name);
        $psword = input($psword);

        if ($con_psword !== $psword) {
            $con_pswordEr = "Password does not match";
        }
    }





    ?>

    <span class="error">* required field</span>
    <br><br>


    <form action="class_exercise_2.2.php" method="post">

        First Name: <input type="text" name="first_name" pattern="[a-zA-Z]{1,}" title="Only letters are allowed" required>
        <span class="error">*
            <?php echo $f_nameEr; ?>
        </span>
        <br><br>


        Last Name: <input type="text" name="last_name" pattern="[a-zA-Z]{1,}" title="Only letters are allowed" required>
        <span class="error">*
            <?php echo $l_nameEr; ?>
        </span>
        <br><br>


        Date of Birth:
        <input type="number" name="day" min="01" max="31" pattern="\d{2}" placeholder="DD" title="Only 2 numbers are allowed" required>
        / <input type="number" name="month" min="01" max="12" pattern="\d{2}" placeholder="MM" title="Only 2 numbers are allowed" required>
        / <input type="number" name="year" min="1900" max="2023" pattern="\d{4}" placeholder="YYYY" title="Only 4 numbers are allowed" required>
        <span class="error">*</span>

        <br><br>


        Gender:
        <input type="radio" name="gender" value="Male" required>Male
        <input type="radio" name="gender" value="Female" required>Female
        <span class="error">*
            <?php echo $genEr; ?>
        </span>
        <br><br>


        Username:
        <input type="text" name="username" pattern="^[^0-9][a-zA-Z0-9_-]{6,}$" title="" required>
        <span class="error">*
            <p class="requirement">
                - minimum 6 characters <br>
                - first character cannot be number <br>
                - only _ or - is allowed in between
            </p>
            <?php echo $u_nameEr; ?>
        </span>
        <br>


        Password:
        <input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]){8,}" required>
        <span class="error">*
            <p class="requirement">
                - minimum 8 characters <br>
                - at least 1 capital letter, 1 small letter, 1 number <br>
                - NO symbols like +$()%@# allowed
            </p>
            <?php echo $pswordEr; ?>
        </span>
        <br>


        Confirm Password:
        <input type="password" name="confirm_password" required>
        <span class="error">*
            <?php echo $con_pswordEr; ?>
        </span>
        <br><br>


        Email: <input type="text" name="email" placeholder="example@email.com" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" required>
        <span class="error">*
            <?php echo $emailEr; ?>
        </span>
        <br><br><br>


        <input type="submit">

        <br><br>



        <!-- output -->

        <?php
        echo "First Name: " . $f_name . "<br>";
        echo "Last Name: " . $l_name . "<br><br>";

        echo "Birthday: " . $bdate . "<br>";

        echo "Gender: " . $gender . "<br>";

        echo "Username: " . $u_name . "<br>";

        echo "Password: " . $psword . "<br>";

        echo "Confirm Password: " . $con_psword . "<br>";

        echo "Email: " . $email;


        ?>











    </form>
</body>

</html>