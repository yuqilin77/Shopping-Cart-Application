<?php
    // Start the session
    session_start();

    // Destroy the current session
    session_destroy();

    // Redirect the user to the index.php (or home) page after logout
    header("Location: index.php");
    exit();
?>