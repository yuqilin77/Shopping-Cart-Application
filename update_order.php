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

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
        // Get the order ID from the form
        $order_id = $_POST['order_id'];

        // Initialize arrays to store product details
        $original_product_order_map = array();
        $update_product_order_map = array();
        $product_name_map = array();

        // Iterate through POST data to extract product details
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'order_quantity_product_') === 0) {
                $product_id = substr($key, strlen('order_quantity_product_'));
                $update_product_order_map[$product_id] = intval($value);
            } else if (strpos($key, 'original_quantity_product_') === 0) {
                $product_id = substr($key, strlen('original_quantity_product_'));
                $original_product_order_map[$product_id] = intval($value);
            } else if (strpos($key, 'name_product_') === 0) {
                $product_id = substr($key, strlen('name_product_'));
                $product_name_map[$product_id] = $value;
            }
        }

        // Initialize variables to store order comments and list
        $comment = '';
        $orderList = '';

        // Initialize an array to store SQL queries
        $sql_product_list = [];

        // Iterate through products to construct comments, order lists, and SQL queries
        foreach ($update_product_order_map as $key => $value) {
            if ($value != 0) {
                $comment = $comment . (isset($product_name_map[$key]) ? $product_name_map[$key] : "") . "*" . $value . ";";
                $orderList = $orderList . $key . "*" . $value . ";";
            }
            
            // Get the original value of the product order
            $original_value = isset($original_product_order_map[$key]) ? $original_product_order_map[$key] : -1;

            // Compare original and updated quantities to construct SQL queries
            if ($value != $original_value) {
                $sql_product_list[] = "UPDATE products SET quantity = quantity + $original_value - $value WHERE id=\"$key\";";
            }
        }

        // Construct SQL query to update the order
        $update_order_query = '';
        if ($comment != '' && $orderList != '') {
            $comment = rtrim($comment, $comment[strlen($comment)-1]);
            $orderList = rtrim($orderList, $orderList[strlen($orderList)-1]);
            $update_order_query = "UPDATE orders SET orderList = \"$orderList\", comment = \"$comment\" WHERE id = \"$order_id\";";
        } else {
            $update_order_query = "UPDATE orders SET orderList = \"$orderList\", comment = \"$comment\", isDeleted = 1 WHERE id = \"$order_id\";";
        }

        try {
            // Start a database transaction
            $db->beginTransaction();

            // Execute the SQL queries to update products and orders
            foreach ($sql_product_list as $sql_query) {
                $db->exec($sql_query);
            }
            $db->exec($update_order_query);

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

    // Redirect back to the admin interface after processing
    header("Location: admin_interface.php");
    exit;
?>