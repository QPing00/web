<!DOCTYPE html>

<html>

    <head>
        
        <style>

            .box{
                display:flex;
                background-color: antiquewhite;
            }

            /* > means inside. div inside the box. */
            .box > div{
                background-color: gery;
                border: 1px solid black;
                margin: 10px;
                padding: 20px;
                /* flex: 1 means divide all boxes equally. */
                flex: 1;
                text-align: center;
            }

            .big{
                font-size: 30px;
                font-weight: bold;
            }

        </style>

    </head>

    <body>
        
        <?php

            $num1 = (rand());
            $num2 = (rand());


            echo "<div class='box'>";
                
                if($num1 > $num2){

                    echo "<div class='big'>";
                        echo "num1 = " . $num1;
                    echo "</div>";

                    echo "<div>";
                        echo "num2 = " . $num2;
                    echo "</div>";

                }else{

                    echo "<div>";
                        echo "num1 = " . $num1;
                    echo "</div>";
                    
                    echo "<div class='big'>";
                        echo "num2 = " . $num2;
                    echo "</div>";

                }
            
            echo "</div>";
        

        ?>

    </body>



</html>