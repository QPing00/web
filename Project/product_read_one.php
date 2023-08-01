<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS →
</head>
<body>
 
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id, name, description, price FROM products WHERE id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
        }

        /*
        $stmt->bindParam(1, $id);

        The first argument (1 in this case): This specifies the parameter's position in the SQL query. 
        In prepared statements, placeholders are typically represented using question marks (?). 
        The parameter's position is the numerical order in which it appears in the query. 
        In this case, 1 means that the first placeholder in the query will be replaced with the value of the parameter.

        The second argument ($id in this case): This is the value that will be bound to the parameter. 
        The value of the $id variable will be inserted into the prepared statement at the position of the first placeholder (?) 
        when the statement is executed. */

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>



        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>

        <!--
            The ENT_QUOTES flag, when used with the htmlspecialchars() or htmlentities() function, 
            indicates that both single and double quotes should be converted to their respective HTML entities. 
            Specifically, it converts:

            Single quote (') to &#039; or &apos;
            Double quote (") to &quot;
        -->


        <!--
            try: The try block is used to enclose a block of code that might throw an exception or cause an error. 
            When an exception or error occurs within the try block, 
            PHP will stop executing the code within the block and immediately jump to the corresponding catch block.

            catch: The catch block is used to handle exceptions or errors that were thrown within the try block. 
            It allows you to gracefully handle exceptional situations, such as database connection failures, 
            file not found errors, or other exceptional conditions. 
            When an exception occurs, PHP will look for the appropriate catch block that can handle the specific type 
            of exception and execute the code within that block.

            die: die is commonly used for early termination of a script when a specific condition is met or 
            when an error occurs and no further execution is possible or needed. 
            The die function outputs a message and terminates the script immediately.
        -->



    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    </body>

</html>