<html>

    <body>

        <form action="class_exercise_1.2" method="get">
            First Name: <input type="text" name="first_name"><br>
            Last Name: <input type="text" name="last_name"><br>
            <input type="submit">
        </form>


        <?php


            if ($_GET){

                $fname = $_GET["first_name"];
                $lname = $_GET["last_name"];
                
                if(empty($fname) || empty($lname)){
                    echo "<p style='color:red'>Please enter your name </p>";
                }else{
                    echo ucwords(strtolower($fname . " "));
                    echo ucwords(strtolower($lname));
                }

            }
                
        ?>

    </body>

</html>