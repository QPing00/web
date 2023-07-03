<html>
    <body>

        <form action="class_exercise_1.1.php" method="get">
            First Name: <input type="text" name="f_name"><br>
            Last Name: <input type="text" name="l_name"><br>
            <input type="submit">
        </form>

        <?php
            if($_GET){
                echo ucwords(strtolower($_GET["f_name"] . " "));
                echo ucwords(strtolower($_GET["l_name"])); 
            }

             // The if($_GET) condition checks if any data was received via the GET method. If the condition is true, it means the form was submitted.
        ?>
    </body>
</html>
