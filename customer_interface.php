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
    <title>Customer Interface</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- The body section -->
<body>
    <?php
        // Display user's name in the header
        echo '<header><h1>Welcome ' .$user_name . ',</h1></header>';
    ?>
    <main>
        <h1>Product List</h1>
        <h2>Search the product</h2>
        <!-- Form for searching products by keyword -->
        <form action="customer_interface.php" method="get" id="search_product_form" style="margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <input type="text" id="searchInput" name="searchInput" placeholder="Enter your search keyword" style="margin-right: 10px;">
                <button type="submit">Search</button>
            </div>
            <input type="hidden" name="keyword" value="">
        </form>
        <!-- Script to capture and update search keyword in hidden input field -->
        <script>
            const searchInput = document.getElementById('searchInput');
            const keywordInput = document.querySelector('input[name="keyword"]');
            const searchForm = document.getElementById('search_product_form');

            searchForm.addEventListener('submit', function(event) {
                keywordInput.value = searchInput.value;
                const savedOrders = localStorage.getItem('savedOrders');
                if (savedOrders) {
                    orders.value = savedOrders;
                }
            });
        </script>
        <!-- Form for placing orders -->
        <form action="place_order.php" method="post" id="place_order_form">
            <?php
                // Retrieve the keyword for product search
                $keyword = "";
                if (isset($_GET['keyword'])) {
                    $keyword = $_GET['keyword'];
                }
                // Search products based on the keyword using the Controller
                $result = $controller->searchProducts($keyword);
                // Display the list of products using the View
                $view->displayProducts($result);
            ?>
            <br>
            <!-- Hidden input field to store the orders -->
            <input type="hidden" name="orders" value="">
            <!-- Display current order -->
            <div id="ordersDisplay">Current Order: </div>
            <!-- Submit button to place the order -->
            <input type="submit" value="Submit Order" onclick="submitOrder()"><br>
        </form>
        <!-- Script to handle order submission and local storage -->
        <script>
            function submitOrder() {
                // Clear the localStorage
                localStorage.clear();
                // Redirect to the place_order.php page
                window.location.href = 'place_order.php';
            }
        </script>
        <!-- Script to handle order quantity and local storage -->
        <script>
            const addButtonList = document.querySelectorAll('.add-to-order');
            const orders = document.querySelector('input[name="orders"]');

            // Function to update the displayed orders
            function updateOrdersDisplay() {
                ordersDisplay.textContent = 'Current Order: ' + orders.value;
            }
            
            // Attach event listeners to add-to-order buttons
            addButtonList.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('product-id');
                    const stockQuantity = this.getAttribute('product-stock');
                    const orderQuantity = document.querySelector('input[name="order_quantity_product_' + productId + '"]');
                    // Validate the order quantity
                    if (parseInt(orderQuantity.value) > parseInt(stockQuantity)) {
                        alert('Order quantity cannot exceed stock quantity.');
                        return;
                    }
                    const orderMap = {};
                    const savedOrders = localStorage.getItem('savedOrders');
                    if (savedOrders) {
                        orders.value = savedOrders;
                    }
                    if (orders.value != "") {
                        const keyValuePairs = orders.value.split(';');
                        keyValuePairs.forEach(pair => {
                            const [key, value] = pair.split('*');
                            orderMap[key] = value;
                        });
                    }
                    orderMap[productId] = orderQuantity.value;
                    if (orderMap[productId] == 0) {
                        delete orderMap[productId];
                    }

                    let updatedOrders = "";
                    for (const key in orderMap) {
                        if (orderMap.hasOwnProperty(key)) {
                            updatedOrders += key + '*' + orderMap[key] + ';';
                        }
                    }
                    orders.value = updatedOrders.slice(0, -1);
                    localStorage.setItem('savedOrders', orders.value);
                    updateOrdersDisplay();
                });
            });

            // Initialize orders from local storage
            document.addEventListener("DOMContentLoaded", function() {
                // Retrieve orders value from local storage if it exists
                const savedOrders = localStorage.getItem('savedOrders');
                if (savedOrders) {
                    orders.value = savedOrders;
                    updateOrdersDisplay();
                }
            });
        </script>
    </main>
    <!-- Navigation links -->
    <p><a href="order_history.php">Past Orders</a></p>
    <p><a href="log_out.php" onclick="logout()">Log out</a></p>
    <script>
        // Function to handle logout and clear local storage
        function logout() {
            // Clear the localStorage
            localStorage.clear();
            // Redirect to the log_out.php page
            window.location.href = 'log_out.php';
        }
    </script>
    <!-- Footer section -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>