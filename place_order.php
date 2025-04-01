<?php 
    // Include the database configuration file
    require_once('database.php');

    // Start or resume the session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is already authenticated
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the error page if not logged in
        $error = "Please login first.";
        include('error.php');
        exit();
    }

    // Retrieve user session variables
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    // Process the order if the request method is POST and necessary data is present
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders']) && $_POST['orders'] != '') {
        // Initialize arrays to store product and order information
        $sql_product_list = [];
        $nameMap = array();
        $orderMap = array();
        $comment = '';

        // Retrieve product id and order quantity from the POST data
        $orders = $_POST['orders'];
        $parts = explode(';', $orders);
        foreach ($parts as $order) {
            list($key, $value) = explode('*', $order);
            $product_id = $key;
            $order_quantity = intval($value);
            // Create SQL queries to update product quantity
            $sql_product_list[] = "UPDATE products SET quantity = quantity - $order_quantity WHERE id=\"$product_id\"";
            $orderMap[$product_id] = $order_quantity;
        }

        // Query the database to retrieve product names for order comment
        $product_ids = array_keys($orderMap);
        $sql_query = "SELECT id, name FROM products WHERE id IN (" . implode(',', $product_ids) . ")";
        try {
            $result = $db->query($sql_query);
        } catch (PDOException $e) {
            // If an exception occurs (e.g., database error), handle it
            $error_message = $e->getMessage();
            include('database_error.php');
            exit();
        }
        
        // Create a mapping of product IDs to product names
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $nameMap[$row['id']] = $row['name'];
        }
        
        // Construct the order comment
        foreach ($orderMap as $key => $value) {
            $comment = $comment . $nameMap[$key] . "*" . $value . ";";
        }
        $comment = rtrim($comment, ';');

        try {
            // Start a database transaction
            $db->beginTransaction();

            // Execute SQL queries to update product quantities and insert the order
            foreach ($sql_product_list as $sql_query) {
                $db->exec($sql_query);
            }

            $sqlQuery = "INSERT INTO orders (userId, orderList, comment) VALUES (:user_id, :order_list, :comment);";
            $stmt = $db->prepare($sqlQuery);
            // Execute the statement with the provided values
            $stmt->execute(array(
                ':user_id' => $user_id,
                ':order_list' => $orders,
                ':comment' => $comment,
            ));

            // Commit the transaction if all queries executed successfully
            $db->commit();
        } catch (PDOException $e) {
            // If an error occurred, rollback the transaction
            $db->rollback();
            $error_message = "Transaction failed: " . $e->getMessage();
            include('database_error.php');
            exit();
        }
    }

    // Redirect back to the customer interface after processing the order
    header("Location: customer_interface.php");
    exit;
?>