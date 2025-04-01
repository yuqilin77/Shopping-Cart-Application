<?php
    // Include necessary files and classes
    require_once('database.php');                // Database configuration file
    require_once('model/Model.php');             // Model class for data interactions
    require_once('view/View.php');               // View class for rendering output
    require_once('controller/Controller.php');   // Controller class for managing actions

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

    // Create an instance of the Model class and pass the database connection
    $model = new Model($db);

    // Create an instance of the Controller class and pass the Model instance
    $controller = new Controller($model);

    // Create an instance of the View class
    $view = new View();

    // Retrieve user details from the session
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html>

<!-- The head section -->
<head>
    <title>Admin Interface</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- The body section -->
<body>
    <?php
        // Display user's name in the header
        echo '<header><h1>[Admin Mode] Welcome ' .$user_name . ',</h1></header>';
    ?>
    <main>
        <center><h1>Order List</h1></center>
        <aside>
            <!-- Display a list of customers -->
            <h2>Customers</h2>
            <nav>
                <ul>
                <?php    
                    // Fetch the list of customers using the Model
                    $result = $model->getAllCustomers();

                    // Use the View to display the list of customers
                    $view->showCustomers($result);
                ?>
                </ul>
            </nav>          
        </aside>
        <section>
            <?php
                // Determine the selected customer's ID
                if (isset($_GET['value'])) {
                    $customer_id = $_GET['value'];
                } else if (isset($_SESSION['customer_id'])) {
                    $customer_id = $_SESSION['customer_id'];
                } else {
                    // If no customer is selected, default to the first customer
                    $result = $model->getAllCustomers();
                    $first_customer = $result->fetch(PDO::FETCH_ASSOC);
                    $customer_id = $first_customer['id'];
                }

                // Store the selected customer's ID in the session
                $_SESSION['customer_id'] = $customer_id;

                // Fetch past orders for the selected customer using the Model
                $result = $model->getOrdersByCustomer($customer_id);

                // Use the View to display past orders
                $view->showPastOrders($result);
            ?>
        </section>
    </main>
    <br>
    <!-- Navigation links for various admin actions -->
    <p><a href="list_all_products.php">List All Products</a></p>
    <p><a href="add_products_form.php">Add Products</a></p>
    <p><a href="update_products_form.php">Update/Delete Products</a></p>
    <p><a href="log_out.php">Log out</a></p>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>