<?php
    // Include necessary files
    require_once('database.php');
    require_once('model/Model.php');
    require_once('view/View.php');
    require_once('controller/Controller.php');

    // Start or resume the session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        // If not authenticated, display an error and exit
        $error = "Please login first.";
        include('error.php');
        exit();
    }

    // Create Model, Controller, and View instances
    $model = new Model($db);
    $controller = new Controller($model);
    $view = new View();
?>

<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>Update/Delete Products</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<!-- the body section -->
<body>
    <header><h1>Update/Delete Product</h1></header>

    <main>
        <h1>Product Details</h1>

        <!-- Form to select a product -->
        <form action="update_products_form.php" method="get" id="admin_select_product_form" style="margin-bottom: 20px;">
            <select name="selected_product_id">
                <?php
                    // Retrieve products from the database and populate the drop-down list
                    $sqlQuery = "SELECT * FROM products ORDER BY id;";
                    $result = $db->query($sqlQuery);

                    while ($product = $result->fetch()) {
                        $product_id = $product['id'];
                        $product_name = $product['name'];
                        echo "<option value='$product_id'>$product_id-$product_name</option>";
                    }
                ?>
            </select><br>
            <br>
            <button type="submit">Show Details</button>
        </form>

        <!-- Form to update a product -->
        <form action="update_product.php" method="post" id="update_product_form">
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["selected_product_id"])) {
                    // Display the update form for the selected product
                    $product_id = $_GET["selected_product_id"];
                    if (isset($_GET['hidden_product_id'])) {
                        $product_id = $_GET['hidden_product_id'];
                    }
                    $result = $model->getProductById($product_id);
                    $view->displayUpdateProduct($result);
                    echo '<br>';
                    echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                    echo '<input type="submit" value="Update Product"><br>';
                }
            ?>
        </form>

        <br>
        
        <!-- Form to delete a product -->
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["selected_product_id"])) {
                echo '<form action="delete_product.php" method="post" id="delete_product_form">';
                echo '<input type="hidden" name="product_id" value="' . $_GET["selected_product_id"] . '">';
                echo '<input type="submit" value="Delete Product"><br>';
                echo '</form>';
            }
        ?>
    </main>

    <!-- Navigation and footer links -->
    <p><a href="admin_interface.php">Admin Page</a></p>
    <p><a href="log_out.php">Log out</a></p>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin.</p>
    </footer>
</body>
</html>