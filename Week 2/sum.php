<!DOCTYPE html>

<html>
    
    <body>
        
        <?php

            $tsum = 0;

            for ($num = 1; $num <= 100; $num++) {
                $tsum += $num; 


                if ($num <= 99 && $num % 2 == 0){  
                    echo "<strong> $num </strong>" . " + ";

                }else if ($num <= 99 && $num % 2 == 1){
                    echo $num . " + ";

                }else if ($num <= 100 && $num % 2 == 0){
                    echo "<strong> $num </strong>" . " = ";

                }

            }

            echo $tsum;
            
            //use $text and rtrim 
            

        ?>

    </body>

</html>