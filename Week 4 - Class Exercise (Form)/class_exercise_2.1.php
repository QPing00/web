<html>

<head>

    <style>
        .error {
            color: red;
        }
    </style>

</head>

<body>

    <form action="class_exercise_2.1" method="get">

        First Name: <input type="text" name="first_name"><br>
        Last Name: <input type="text" name="last_name">


        <h3>Date of Birth: </h3>


        <select id="day" name="day">

            <option value="day">Day</option>

            <?php
            for ($day = 1; $day <= 31; $day++) {
                echo "<option value='$day'> $day </option>";
            }
            ?>

        </select>


        <select id="month" name="month">

            <option value="month">Month</option>

            <?php
            for ($month = 1; $month <= 12; $month++) {
                echo "<option value='$month'> $month </option>";
            }
            ?>

        </select>


        <select id="year" name="year">

            <option value="month">Year</option>

            <?php

            $t_year = date("Y");

            for ($year = 1900; $year <= $t_year; $year++) {
                echo "<option value = '$year'> $year </option>";
            }
            ?>

        </select>


        <br><br>


        <input type="submit">

    </form>




    <?php

    function starSign($day, $month)
    {

        $star = "";

        if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
            $star = "Aries";
        } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $star = "Taurus";
        } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) {
            $star = "Gemini";
        } else if (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) {
            $star = "Cancer";
        } else if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
            $star = "Leo";
        } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $star = "Virgo";
        } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) {
            $star = "Libra";
        } else if (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) {
            $star = "Scorpio";
        } else if (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
            $star = "Sagittarius";
        } else if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
            $star = "Capricorn";
        } else if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $star = "Aquarius";
        } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $star = "Pisces";
        }

        return $star;
    }



    function chineseZodiac($year)
    {

        $zodiac = array("Rat", "Ox", "Tiger", "Rabbit", "Dragon", "Snake", "Horse", "Sheep", "Monkey", "Rooster", "Dog", "Pig");

        $remainder = ($year - 1900) % 12;

        return $zodiac[$remainder];
    }



    if ($_GET) {

        $fname = $_GET['first_name'];
        $lname = $_GET['last_name'];

        $day = intval($_GET['day']);
        $month = intval($_GET['month']);
        $year = intval($_GET['year']);
        //The intval() function returns the integer value of a variable.


        if (empty($fname) || empty($lname)) {
            echo "<span class='error'>Please enter your name.</span>";
            echo "<br><br>";
        } else {
            echo "First Name: " . ucwords(strtolower($fname . " "));
            echo "<br>";
            echo "Last Name: " . ucwords(strtolower($lname));
            echo "<br><br>";
        }


        $date = checkdate($day, $month, $year);
        if ($date == true) {
            echo "Date of Birth: " . $day . "-" . $month . "-" . $year . "<br>";
            echo "Star Sign: " . starSign($day, $month) . "<br>";
            echo "Chinese Zodiac: " . chineseZodiac($year);

            $e_year = $t_year - 17;
            // 2006-2023

            for ($i = $e_year; $i <= $t_year; $i++) {

                if ($year == $i) {
                    echo "<p class='error'>User is below 18 years old.</p>";
                }
            }
        } else {
            echo "Date of Birth: <span class='error'>Invalid</span> <br>";
            echo "Star Sign: - <br>";
            echo "Chinese Zodiac: - <br>";
        }
    }
    ?>

</body>

</html>