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
        <!-- Main content area for displaying the error message -->
        <h1>Database Error</h1>
        <!-- Paragraphs explaining the error and possible solutions -->
        <p>There was an error connecting to the database.</p>
        <p>The database must be installed as described in the appendix.</p>
        <p>MySQL must be running as described in chapter 1.</p>
        <!-- Display the specific error message retrieved from PHP code -->
        <p>Error message: <?php echo $error_message; ?></p>
        <p>&nbsp;</p>
    </main>

    <!-- Footer section with the copyright information -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>
