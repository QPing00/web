<html>

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

?>


<head>

    <style>
        .error {
            color: red;
        }

        .requirement {
            font-size: 13px;
            color: black;
        }
    </style>

</head>


<body>

    <?php

    $f_nameEr = $l_nameEr = $b_dayEr =  $genEr = $u_nameEr = $passwordEr = $confirm_passwordEr = $emailEr = "";
    $f_name = $l_name = $bday = $gender = $u_name = $password = $confirm_password = $email = "";


    if ($_POST) {

        //first name
        $f_name = $_POST['f_name'];
        $f_name = ucwords(strtolower(input($f_name)));

        if (empty($f_name)) {
            $f_nameEr = "Please enter your first name";
        }


        //last name
        $l_name = $_POST['l_name'];
        $l_name = ucwords(strtolower(input($l_name)));

        if (empty($l_name)) {
            $l_nameEr = "Please enter your last name";
        }


        //birthday
        $day = intval($_POST['day']);
        $month = intval($_POST['month']);
        $year = intval($_POST['year']);

        $date = checkdate($day, $month, $year);
        if ($date == true) {
            $bday = $day . "/" . $month . "/" . $year;
        } else {
            $bday = $day . "/" . $month . "/" . $year;
            $b_dayEr = "Invalid date";
        }


        //Gender
        $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
        if (empty($gender)) {
            $genEr = "Please select a gender";
        }


        //Username
        $u_name = input($_POST['u_name']);

        $charlist = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ ');
        //The str_split() function is used to split the string into an array of individual characters. 
        //This array contains all the allowed characters as separate elements.
        // https://www.w3schools.com/php/func_string_str_split.asp

        $remove_charList = str_replace($charlist, '', $u_name);
        //str_replace(find,replace,string)
        //Remove all allowed characters from the username
        //The str_replace() function replaces all occurrences of the characters in the $charlist array with an empty string in the $username. 
        //It effectively removes all allowed characters from the username.
        //The resulting string with allowed characters removed is assigned to the variable $remove_charList
        //https://www.w3schools.com/PHP/func_string_str_replace.asp 

        //After this line of code, the $remove_charList will contain the original $username string with all allowed characters removed. 
        //If the username contains only allowed characters, the $remove_charList will be an empty string. 
        //If there are disallowed characters in the username, $remove_charList will contain those disallowed characters.
        //This makes it easy to check if the username contains only allowed characters by checking if $remove_charList is an empty string.

        if (empty($u_name)) {
            $u_nameEr = "Please enter a username";
        } else if (strlen($u_name) < 6) {
            $u_nameEr = "Minimum 6 characters required";
        } else if (is_numeric($u_name[0])) {
            $u_nameEr = "First character cannot be a number";
        } else if (strlen($remove_charList) !== 0) {
            //If the length of the sanitized username is 0, it means there are no disallowed characters
            $u_nameEr = "Contain disallowed characters";
        } else if ($u_name[0] == '-' || $u_name[0] == '_') {
            $u_nameEr = "First character cannot be a symbol";
        } else if (substr($u_name, -1) == '-' || substr($u_name, -1) == '_') {
            $u_nameEr = "Last character cannot be a symbol";
            //substr(string,start)
            //The substr() function returns a part of a string.
            //The second parameter is -1, which indicates that we want to start from the last character of the string and move one character backward.
            //If the $username contains "JohnDoe-", substr($username, -1) will return the last character of the string, which is -.
            // https://www.w3schools.com/PHP/func_string_substr.asp
        }


        //Password
        $password = $_POST['password'];

        if (empty($password)) {
            $passwordEr = 'Please enter a password';
        } else if (strlen($password) < 8) {
            $passwordEr = 'Minimum 8 characters required';
        } else if (strpbrk($password, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ') == false) {
            $passwordEr = 'Must contain at least one upper case letter';
            // strpbrk(string,charlist)
            // The strpbrk() function searches a string for any of the specified characters.
            // This function returns the rest of the string from where it found the first occurrence of a specified character, otherwise it returns FALSE.
            // https://www.w3schools.com/php/func_string_strpbrk.asp
        } else if (strpbrk($password, 'abcdefghijklmnopqrstuvwxyz') == false) {
            $passwordEr = 'Must contain at least one lower case letter';
        } else if (strpbrk($password, '0123456789') == false) {
            $passwordEr = 'Must contain at least one number';
        } else if (strpbrk($password, '+$()%@#') == true) {
            $passwordEr = 'No symbols like +$()%@# allowed';
        }


        //Confirm Password
        $confirm_password = $_POST['confirm_password'];

        if (empty($confirm_password)) {
            $confirm_passwordEr = 'Please confirm your password';
        } else if ($confirm_password !== $password) {
            $confirm_passwordEr = 'Password does not match';
        }


        //Email
        $email = $_POST['email'];

        if (empty($email)) {
            $emailEr = 'Please enter an email address';
        } else if (substr_count($email, '@') !== 1) {
            $emailEr = 'Email format incorrect';
            //substr_count(string,substring)
            //The substr_count() function counts the number of times a substring occurs in a string.
            //https://www.w3schools.com/PHP/func_string_substr_count.asp
        }
    }

    ?>


    <!-- html -->

    <span class="error">* required field</span>

    <br><br>

    <form action="class_exercise_2.2.php" method="post">

        First Name: <input type="text" name="f_name">
        <span class="error">*
            <?php echo $f_nameEr; ?>
        </span>

        <br><br>


        Last Name: <input type="text" name="l_name">
        <span class="error">*
            <?php echo $l_nameEr; ?>
        </span>

        <br><br>


        Date of Birth:

        <select name="day" id="day">

            <option value="day">Day</option>

            <?php
            for ($day = 1; $day <= 31; $day++) {
                echo "<option value='$day'>$day</option>";
            }
            ?>

        </select>

        <select name="month" id="month">

            <option value="month">Month</option>

            <?php
            for ($month = 1; $month <= 12; $month++) {
                echo "<option value='$month'>$month</option>";
            }
            ?>

        </select>

        <select name="year" id="year">

            <option value="year">Year</option>

            <?php
            for ($year = 1900; $year <= 2023; $year++) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select>

        <span class="error">*
            <?php echo $b_dayEr; ?>
        </span>

        <br><br>


        Gender:
        <input type="radio" name="gender" value="Male">Male
        <input type="radio" name="gender" value="Female">Female
        <span class="error">*
            <?php echo $genEr; ?>
        </span>

        <br><br>


        Username: <input type="text" name="u_name">
        <span class="error">*
            <?php echo $u_nameEr; ?>
        </span><br>
        <p class="requirement">
            - minimum 6 characters <br>
            - first character cannot be number <br>
            - only _ or - is allowed in between
        </p>

        <br>


        Password: <input type="password" name="password">
        <span class="error">*
            <?php echo $passwordEr; ?>
        </span><br>
        <p class="requirement">
            - minimum 8 characters <br>
            - at least 1 capital letter, 1 small letter, 1 number <br>
            - NO symbols like +$()%@# allowed
        </p>

        <br>


        Confirm Password: <input type="password" name="confirm_password">
        <span class="error">*
            <?php echo $confirm_passwordEr; ?>
        </span>

        <br><br>


        Email: <input type="text" name="email" placeholder="example@email.com">
        <span class="error">*
            <?php echo $emailEr; ?>
        </span>
        <br><br>


        <input type="submit">

        <br><br><br>



        <!-- Output -->

        <?php

        echo "First Name: " . $f_name . "<br>";

        echo "Last Name: " . $l_name . "<br>";

        echo "Date of Birth: " . $bday . "<br>";

        echo "Gender: " . $gender . "<br>";

        echo "Username: " . $u_name . "<br>";

        echo "Password: " . $password . "<br>";

        echo "Confirm Password: " . $confirm_password . "<br>";

        echo "Email: " . $email . "<br>";

        ?>

    </form>

</body>

</html>