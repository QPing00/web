<?php
include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Read Category</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
</head>

<body>
    <?php
    include 'navigation.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Categories</h1>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="category_create.php" class="btn btn-primary m-b-1em">Create New Category</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>


        <?php
        include 'config/database.php';

        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        } else if ($action == 'failToDelete') {
            echo "<div class='alert alert-danger'>Category in used cannot be deleted.</div>";
        }

        $query = "SELECT * FROM categories 
            ORDER BY category_id ASC";

        if ($_GET && isset($_GET['search'])) {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please enter a product keyword</div>";
            }

            $query = "SELECT * FROM categories 
            WHERE category_id LIKE '$search' OR
            category_name LIKE '%$search%' OR
            description LIKE '%$search%'
            ORDER BY category_id ASC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            echo "<tr>";
            echo "<th>Category Id</th>";
            echo "<th>Category Name</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$category_id}</td>";
                echo "<td>{$category_name}</td>";

                echo "<td>";
                echo "<a href='category_read_one.php?category_id={$category_id}' class='btn btn-info' style='margin-right: 0.5em;'>Read</a>";
                echo "<a href='category_update.php?category_id={$category_id}' class='btn btn-primary' style='margin-right: 0.5em; margin-top: 0.5em; margin-bottom:0.5em;'>Edit</a>";
                echo "<a href='#' onclick='delete_category({$category_id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_category(category_id) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'category_delete.php?category_id=' + category_id;
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- confirm delete record will be here -->

</body>

</html>