<!DOCTYPE html>
<html>

<!-- The head section -->
<head>
    <title>Oops...</title>
    <!-- Link to the main stylesheet -->
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- The body section -->
<body>
    <!-- Header section with the title of the application -->
    <header><h1>Shopping Cart Application</h1></header>

    <main>
        <!-- Main content area for displaying error information -->
        <h2 class="top">Error</h2>
        <!-- Display the error message received from PHP code -->
        <p><?php echo $error; ?></p>
    </main>

    <!-- Footer section with the copyright information -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>