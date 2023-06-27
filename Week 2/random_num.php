<!DOCTYPE html>
<html>

    <head>
        <style>
            #num1 {color: green; font-style: italic;} 
            #num2 {color: blue; font-style: italic;}
            #sum {color: red; font-style: bold;}
            #multi {color: black; font-style: italic; font-weight: bold;}
        </style>
    </head>

    <body>
        
        <?php
            echo "<div id='num1'>";
                $num1 = (rand(100,200));
                echo "num1 = " . $num1;
            echo "</div>";  


            echo "<div id='num2'>";
                $num2 = (rand(100,200));
                echo "num2 = " . $num2;
            echo "</div>"; 
            

            echo "<div id='sum'>";
                echo "sum = " . $num1 + $num2;
            echo "</div>"; 


            echo "<div id='multi'>";
                echo "multiplication = " . $num1 * $num2;
            echo "</div>"; 

        ?>

    </body>

</html>