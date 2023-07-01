<html>
    <body>

        <form action="form.php" method="get">
            First Name: <input type="text" name="f_name"><br>
            Last Name: <input type="text" name="l_name"><br>
            <input type="submit">
        </form>

        <?php
            if($_GET){
                echo ucwords(strtolower($_GET["f_name"] . " "));
                echo ucwords(strtolower($_GET["l_name"])); 
            }

            //if有value了（意思是有submit了 / $_GET == true），才会run这个if
        ?>
    </body>
</html>