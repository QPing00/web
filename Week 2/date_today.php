<!DOCTYPE html>

    <body>

            
        <select id="day" name="day">

            <option value = "">Day</option>

            <?php 

                $selected_day = date('d'); //current day

                for ($day = 1; $day <= 31; $day++){
                    $selected = ($day == $selected_day) ? 'selected' : '';
                    echo "<option value='$day' $selected> $day </option>";
                }

                //The ?: ternary operator, also known as the conditional operator or shorthand if-else, allows you to conditionally assign a value based on a condition.
                //(condition) ? value_if_true : value_if_false;

            ?>

        </select>



        <select id="month" name="month">

            <option value = "">Month</option>

            <?php

                $selected_month = date('m'); //current month

                for ($month = 1; $month <= 12; $month++){
                    $selected = ($month == $selected_month) ? 'selected' : '';
                    echo "<option value='$month' $selected> $month </option>";
                }

            ?>

        </select>



        <select id="year" name="year">

            <option value = "">Year</option>

            <?php

                $selected_year = date('Y'); //current year

                for ($year = 1923; $year <= 2023; $year++){
                    $selected = ($year == $selected_year) ? 'selected' : '';
                    echo "<option value='$year' $selected> $year </option>";
                }

            ?>

        </select>



    </body>

</html>