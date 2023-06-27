<!DOCTYPE html>

<body>
    

    <select id = 'days' name = 'days'>

        <option value="day">Day</option>

        <?php 

            for ($day = 1; $day <=31; $day++){
                echo "<option value='$day'>$day</option>";
            }

        ?>

    </select>


    <!-- An array is a special variable, which can hold more than one value at a time. -->


    <select name = 'months'>

        <option value="month">Month</option>

        <?php

            $month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'Octuber', 'November', 'December');
            echo "<option value='1'>$month[0]</option>";
            echo "<option value='2'>$month[1]</option>";
            echo "<option value='3'>$month[2]</option>";
            echo "<option value='$month[3]'>$month[3]</option>";
            echo "<option value='$month[4]'>$month[4]</option>";
            echo "<option value='$month[5]'>$month[5]</option>";
            echo "<option value='$month[6]'>$month[6]</option>";
            echo "<option value='$month[7]'>$month[7]</option>";
            echo "<option value='$month[8]'>$month[8]</option>";
            echo "<option value='$month[9]'>$month[9]</option>";
            echo "<option value='$month[10]'>$month[10]</option>";
            echo "<option value='$month[11]'>$month[11]</option>";
        ?>   
        <!-- use loop  -->

    </select>


    <select name = 'years'>

        <option value="year">Year</option>

        <?php

            for ($year = 1990; $year <=2023; $year++){
                echo "<option>$year</option>";
            }

        ?>

    </select>

</body>

</html>