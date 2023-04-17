<?php
session_start();

// check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// connect to the database
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
    exit();
}
$sql = "SELECT * FROM student";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>