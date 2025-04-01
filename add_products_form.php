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
?>

<!DOCTYPE html>
<html>

<!-- The head section containing metadata and external stylesheets -->
<head>
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<!-- The body section -->
<body>
    <!-- Header section with the title of the page -->
    <header><h1>Add Product</h1></header>

    <main>
        <!-- Main content area for adding product details -->
        <h1>Product Details</h1>
        <!-- Form for adding product information, submits to 'add_products.php' -->
        <form action="add_products.php" method="post" id="add_products_form">
            <!-- Input fields for product name, description, price, and stock -->
            <label>Name:</label>
            <input type="text" name="product_name" required><br>
            <label>Description:</label>
            <input type="text" name="product_description" width="200"><br>
            <label>Price:</label>
            <input type="number" name="product_price" step="0.01" min="0" required><br>
            <label>Stock:</label>
            <input type="number" name="product_stock" step="1" min="0" required><br>
            <!-- Submit button for adding the product -->
            <input type="submit" value="Add Product"><br>
        </form>
    </main>
    
    <!-- Links to the admin interface page and logout page -->
    <p><a href="admin_interface.php">Admin Page</a></p>
    <p><a href="log_out.php">Log out</a></p>
    
    <!-- Footer section with copyright information -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin.</p>
    </footer>
</body>
</html>