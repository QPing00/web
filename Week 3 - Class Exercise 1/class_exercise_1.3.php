<!DOCTYPE html>

    <body>

        <form action="class_exercise_1.3.php" method="get">
            Number 1: <input type="text" name="number_1"><br>
            Number 2: <input type="text" name="number_2"><br>
            <input type="submit">
        </form>


        <?php

            if($_GET){

                $num1 = $_GET["number_1"];
                $num2 = $_GET["number_2"];

                if(!is_numeric($num1) || !is_numeric($num2)){
                    echo "<p style = 'color:red'> Please fill in a number </p>";
                }else{
                    $sum = $num1 + $num2;
                    echo $sum;
                }
            }

        ?>

    </body>

</html>