<?php
    // Include the database configuration file
    require_once('database.php');

    // Check if a session is already active, if not, start a new session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user is authenticated (logged in)
    if (!isset($_SESSION['user_id'])) {
        // If not authenticated, set an error message and include an error page, then exit
        $error = "Please login first.";
        include('error.php');
        exit();
    }
    
    // Check if the request method is POST and if product ID is provided
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        // Retrieve the product ID from the POST data
        $product_id = $_POST['product_id'];
        
        // SQL query to mark the product as deleted in the database
        $sql_query = "UPDATE products SET isDeleted=1 WHERE id=\"$product_id\";";
        try {
            // Execute the query
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
    
    // Redirect to the product list page after processing
    header("Location: list_all_products.php");
    exit;
?>