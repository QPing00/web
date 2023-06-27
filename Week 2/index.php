<!DOCTYPE html>
<html>

    <head>
        <style>
            span {color: red; font-weight: bold;} 
        </style>
    </head>

    <body>
        
        <?php
        echo "My first <span> PHP </span> script!";
        echo "<br>";
        echo date("F j, Y (l)") ; 
        echo "<br>";
        date_default_timezone_set("Asia/Kuala_Lumpur");
        echo date("h:ia");
        ?>

    </body>

</html>