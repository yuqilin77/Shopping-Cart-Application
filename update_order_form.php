<?php
    // Include the necessary files for database connection, MVC components, and session handling
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

    // Create instances of Model, View, and Controller
    $model = new Model($db);
    $controller = new Controller($model);
    $view = new View();

    // Get user information from the session
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Order</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>
<body>
    <?php
        // Check if the update button was clicked and display order information if available
        if (isset($_POST['update']) && isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
            // Display order header with IDs
            echo '<header><h1>Order id: ' .$order_id . ' from customer id: ' . $_SESSION['customer_id'] . '</h1></header>';
        }
    ?>
    <main>
        <h1>Order Details</h1>
        <!-- Form to update order -->
        <form action="update_order.php" method="post" id="update_order_form">
        <?php
            // Initialize arrays to store product details
            $isdeletedMap = array();
            $nameMap = array();
            $stockMap = array();
            $orderMap = array();
            
            // Fetch order details from the Model
            $result = $model->getOrderById($_POST['order_id']);
            $order = $result->fetch(PDO::FETCH_ASSOC);
            
            // Parse order list and populate product details arrays
            $parts = explode(';', $order['orderList']);
            foreach ($parts as $order) {
                list($key, $value) = explode('*', $order);
                $product_id = $key;
                $order_quantity = intval($value);
                $orderMap[$product_id] = $order_quantity;
            }
            
            // Retrieve product details from the database and populate arrays
            $product_ids = array_keys($orderMap);
            $sql_query = "SELECT * FROM products WHERE id IN (" . implode(',', $product_ids) . ") ORDER BY id;";
            try {
                $result = $db->query($sql_query);
            } catch (PDOException $e) {
                // Handle database error
                $error_message = $e->getMessage();
                include('database_error.php');
                exit();
            }
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $nameMap[$row['id']] = $row['name'];
                $stockMap[$row['id']] = $row['quantity'];
                $isdeletedMap[$row['id']] = $row['isDeleted'];
            }
            
            // Call the View's displayUpdateOrder method to render the order details
            $view->displayUpdateOrder($orderMap, $nameMap, $stockMap, $isdeletedMap);
        ?>
        <br>
        <!-- Hidden input field to carry the order ID -->
        <?php echo '<input type="hidden" name="order_id" value="' . $order_id . '">' ?>
        <!-- Update button -->
        <input type="submit" value="Update"><br>
        </form>
        <p>
    </main>
    <!-- Links to admin page and logout -->
    <p><a href="admin_interface.php">Admin Page</a></p>
    <p><a href="log_out.php">Log out</a></p>
    <!-- Footer with copyright information -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>
