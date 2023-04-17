<?php
session_start();

// check if the user is already logged in
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header("Location: blackboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head style="text-align: center;">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Your Username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Your Password" required>

            <div>
                <input type="submit" name="submit" value="Login">
                <input type="reset" value="Clear">
            </div>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
