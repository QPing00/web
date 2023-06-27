<!DOCTYPE html>

<!-- https://www.w3schools.com/PHP/func_date_date_format.asp -->
<!-- https://www.w3schools.com/php/php_date.asp -->

    <body>

        <?php
            echo "<span style='color: brown; font-weight: bold; text-transform: uppercase'>" . date("M ") . "</span>"; 
            echo "<span style='font-weight: bold;'>" . date("d, Y ") . "</span>"; 
            echo "<span style='color: darkblue;'>" . date("(D)") . "</span>";  
            echo "<br>";
            date_default_timezone_set("Asia/Kuala_Lumpur");
            echo date("H:i:s");
        ?>

    </body>

</html>