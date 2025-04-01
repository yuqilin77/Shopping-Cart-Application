<?php 
    // Start the session or resume the existing session
    session_start();
    
    // Check if the user is already authenticated (logged in)
    if (isset($_SESSION['user_id'])) {
        // Redirect the user based on their role (admin or customer)
        if ($_SESSION['is_admin']) {
            // Redirect admin users to the admin interface
            header("Location: admin_interface.php");
        } else {
            // Redirect non-admin users (customers) to the customer interface
            header("Location: customer_interface.php");        
        }
        // Terminate script execution after redirection
        exit();
    }

    // Include the database configuration file
    require_once('database.php');
    
    // Initialize the $error variable
    unset($error);

    // Check if the request method is POST (when a form is submitted)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve username, password, and isAdmin status from the POST data
        $user_name = $_POST['username'];
        $user_password = $_POST['password'];
        // Check if the 'is_admin' checkbox is checked in the form
        if (isset($_POST['is_admin'])) {
            $is_admin = true;
        } else {
            $is_admin = false;
        }

        // Prepare and execute a SQL query to check user credentials
        $sql_query = "SELECT * FROM users WHERE name=\"$user_name\" AND password=\"$user_password\" AND isAdmin=\"$is_admin\"";
        try {
            $result = $db->query($sql_query);
        } catch (PDOException $e) {
            // If an exception occurs (e.g., database error), handle it
            $error_message = $e->getMessage();
            // Display a database error page
            include('database_error.php');
            // Terminate script execution
            exit();
        }
    }

    // Check if the user record exists
    if (!isset($result)) {  
        // Set an error message if there is an issue with the database
        $error = "Please login first.";
    } else if ($result->rowCount() == 0) {
        // Set an error message if the login credentials are incorrect
        $error = "Login failed. Please check your login credentials.";
    }
    
    // If an error is detected, display the error page and exit
    if (isset($error)) {
        include('error.php');
        exit();
    }

    // Fetch the first matching user record from the query result
    $first_match = $result->fetch(PDO::FETCH_ASSOC);
    $user_id = $first_match['id'];
    // Store user information in the session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['is_admin'] = $is_admin;
    
    // Redirect the user to the appropriate interface based on their role
    if ($is_admin) {
        // Redirect admin users to the admin interface
        header("Location: admin_interface.php");
    } else {
        // Redirect non-admin users (customers) to the customer interface
        header("Location: customer_interface.php");
    }
    // Terminate script execution after redirection
    exit();
?>