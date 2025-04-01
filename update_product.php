<?php
    // Include the database configuration file
    require_once('database.php');

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

    // Check if the request method is POST and necessary data is present
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        // Extract data from the POST request
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_stock = $_POST['product_stock'];
        $product_deleted = $_POST['product_deleted'] == "true" ? 1 : 0;
        
        // Construct the SQL query to update the product
        $sql_query = "UPDATE products SET name=\"$product_name\", price =\"$product_price\", description =\"$product_description\", quantity = \"$product_stock\", isDeleted = \"$product_deleted\" where id=\"$product_id\";";

        try {
            // Execute the SQL query
            $result = $db->query($sql_query);
        } catch (PDOException $e) {
            // If an exception occurs (e.g., database error), handle it
            $error_message = $e->getMessage();
            // Display an error page
            include('database_error.php');
            // Terminate script execution
            exit();
        }
    }

    // Redirect back to the list of all products after updating
    header("Location: list_all_products.php");
    exit;
?>