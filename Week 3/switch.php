<!DOCTYPE html>

<!-- https://www.w3schools.com/php/php_switch.asp -->

<html>


    <body>

        <?php

            $score = "80";

            echo $score . " ";


            if (is_numeric($score)){
                switch ($score){

                    case 100:
                        echo "Well Done";
                        // break;
                    
                    case $score >= 60 :
                        echo "Pass";
                        break;

                    case $score < 60 && $score>= 0:
                        echo "Fail";
                        break;

                    default: 
                        echo "Invalid";
                        // if the score is >100 or <0
                    }
            }else{
                echo "Invalid";
            }
        ?>

        </body>

</html>
