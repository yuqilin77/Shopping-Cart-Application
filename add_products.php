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

    // Check if the HTTP request method is POST (indicating form submission)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Retrieve product details from the submitted form data
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_stock = $_POST['product_stock'];

        // Define the SQL query to insert a new product into the database
        $sqlQuery = "INSERT INTO products (name, description, price, quantity) VALUES (:product_name, :product_description, :product_price, :product_stock);";
        try {
            // Prepare the SQL statement
            $stmt = $db->prepare($sqlQuery);

            // Execute the statement with the provided values
            $stmt->execute(array(
                ':product_name' => $product_name,
                ':product_description' => $product_description,
                ':product_price' => $product_price,
                ':product_stock' => $product_stock,
            ));
        } catch (PDOException $e) {
            // If an exception occurs (e.g., database error), handle it
            $error_message = $e->getMessage();

            // Display an error page specific to database errors
            include('database_error.php');

            // Terminate script execution
            exit();
        }
    }

    // Redirect to the page that lists all products after successful addition
    header("Location: list_all_products.php");
    exit;
?>