<?php
// start the session
session_start();

$dsn = 'mysql:host=localhost;port=3307;dbname=phpmyadmin';
$username = 'root';
$password = 'abc123';

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // validate user input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // sanitize user input
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // validate password
    if ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // check if the email already exists in the database
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = "An account with that email already exists.";
        } else {
            // insert the new user into the database
            $sql = "INSERT INTO user (username, email, password, created_at, updated_at) VALUES (:username, :email, :password, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password_hash]);

            // set the user id in the session and redirect to the dashboard
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form method="post" action="" class="form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirm Password:</label>
                <input type="password" name="password_confirm" id="password_confirm" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
            <?php if (isset($error)) { ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div>
            <?php } ?>
        </form>
    </div>
</body>
</html>
