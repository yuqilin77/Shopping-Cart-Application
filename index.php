<!DOCTYPE html>
<html>

<!-- The head section -->
<head>
    <title>Sign in</title>
    <!-- Link to the main stylesheet -->
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- The body section -->
<body>
    <!-- Header section with the title of the sign-in page -->
    <header><h1>Sign in</h1></header>

    <main>
        <!-- Main content area containing the sign-in form -->
        <form action="login.php" method="post" id="login_form">
            
            <!-- Label and input field for entering the username -->
            <label>Username:</label>
            <input type="text" name="username"><br>

            <!-- Label and input field for entering the password -->
            <label>Password:</label>
            <input type="password" name="password"><br>

            <!-- Label and checkbox for indicating admin status -->
            <label>Is Admin:</label>
            <input type="checkbox" name="is_admin"><br>

            <!-- Submit button to initiate the login process -->
            <input type="submit" value="Login"><br>
        </form>
    </main>

    <!-- Footer section with the copyright information -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Yuqi Lin</p>
    </footer>
</body>
</html>