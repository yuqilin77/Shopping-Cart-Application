<?php
    // Database connection settings

    // DSN (Data Source Name) specifying MySQL host and database name
    $dsn = 'mysql:host=localhost;dbname=cs602termprojectdb';
    // Username for database authentication
    $username = 'cs602_user';
    // Password for database authentication
    $password = 'cs602_secret';

    try {
        // Attempt to create a PDO database connection using provided credentials
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        // If a PDOException is thrown (database connection error), handle it
        // Get the error message from the exception
        $error_message = $e->getMessage();
        // Include the database error template to display the error message
        include('database_error.php');
        // Terminate script execution after displaying the error message
        exit();
    }
?>