<?php
function area($r)
{
    return pow($r, 2) * M_PI;
}
?>


<html>

<body>

    <form action="circle_area.php" method="get">
        Radius: <input type="text" name="radius">
    </form>

    <?php

    if ($_GET) {

        $r = $_GET["radius"];

        if (!is_numeric($r)) {
            echo "<p style = 'color: red'> Please fill in a number </p>";
        } else {
            echo "circle area = " . area($r);
        }
    }

    ?>
</body>

</html>