<!DOCTYPE html>

    <body>
        
        <form action="class_exercise_1.4.php" method="get">
            Number: <input type="text" name="number">
            <input type="submit">
        </form>

        <?php

            if($_GET){

                $num = $_GET["number"];

                if(!is_numeric($num)){
                    echo "<p style = 'color: red'> Please fill in a number </p>";
                
                }else{

                    $sum = 0;

                    for ($i = $num; $i>=1; $i--){
                        $sum += $i;

                        if ($i > 1) {
                            echo $i . " + ";
                        } else {
                            echo $i . " = " . $sum;
                        }
                    }

                }   

            }

        ?>

    </body>

</html>