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

    // Retrieve user information from the session
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    
    // Query to retrieve all products from the database, ordered by ID
    $products_stmt = $db->query("SELECT * FROM products ORDER BY id;");
    // Fetch all product records as an associative array
    $products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<!-- The head section -->
<head>
    <title>Product List</title>
    <!-- Link to the main stylesheet -->
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- The body section -->
<body>
    <!-- Header section with the title of the product list page -->
    <header><h1>Product List</h1></header>
    
    <main>
        <!-- Main content area for displaying product details in a table -->
        <h1>Product Details</h1>
        <table>
            <tr>
                <!-- Table header row -->
                <th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock Qty</th><th>Deleted</th>
            </tr>
            <?php
                // Loop through each product and display their details in a table row
                foreach ($products as $product) {
                    echo '<tr>';
                    echo '<td>' . $product['id'] . '</td>';
                    echo '<td>' . $product['name'] . '</td>';
                    echo '<td>' . $product['description'] . '</td>';
                    echo '<td>' . $product['price'] . '</td>';
                    echo '<td>' . $product['quantity'] . '</td>';
                    // Display "true" if the product is deleted, otherwise "false"
                    echo '<td>' . ($product['isDeleted'] == 0 ? "false" : "true") . '</td>';
                    echo '</tr>';
                }
            ?>
        </table>
        <br>
        </form>
        <p>
    </main>

    <!-- Navigation links for admin actions and log out -->
    <p><a href="admin_interface.php">Admin Page</a></p>
    <p><a href="log_out.php">Log out</a></p>
</body>
</html>