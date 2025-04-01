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

    // Query the database for the user's past orders
    $orders_stmt = $db->query("SELECT * FROM orders WHERE userId=\"$user_id\" ORDER BY isDeleted, id DESC;");
    $past_orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>Order History</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- the body section -->
<body>
    <?php
        // Display the user's name in the header
        echo '<header><h1>Welcome ' .$user_name . ',</h1></header>';
    ?>
    <main>
        <h1>Past Orders</h1>
        <table>
            <tr>
                <th>ID</th><th>Order Details</th><th>Deleted By Admin</th>
            </tr>
            <?php
                // Loop through each past order and display their details in a table row
                foreach ($past_orders as $order) {
                    echo '<tr>';
                    echo '<td>' . $order['id'] . '</td>';
                    echo '<td>' . $order['comment'] . '</td>';
                    echo '<td>' . ($order['isDeleted'] == 0 ? "false" : "true") . '</td>';
                    echo '</tr>';
                }
            ?>
        </table>
        <br>
    </main>
    <p><a href="customer_interface.php">Order Product</a></p>
    <p><a href="log_out.php">Log out</a></p>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>