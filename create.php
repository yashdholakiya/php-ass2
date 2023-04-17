<?php
// check if the user is logged in
session_start();
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
}

// handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // validate user input
    $name = htmlspecialchars(trim($_POST['name']));
    $course = htmlspecialchars(trim($_POST['course']));
    $photo = $_FILES['photo']['name'];
    $phone_number = floatval($_POST['Mobile no.']);

    // sanitize user input
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $course = filter_var($course, FILTER_SANITIZE_STRING);
    $phone_number = filter_var($phone_number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // validate the image file
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_types)) {
        echo "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
        exit();
    }

    // move the uploaded image to the server
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);

    // insert the new product into the database
    $sql = "INSERT INTO Product (name, course, photo, phone_number, created_at, updated_at) VALUES (:name, :course, :photo, :phone_number, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Student name' => $name, 'Course' => $course, 'Photo' => $photo, 'Mobile no.' => $phone_number]);

    // redirect to the product list page
    header("Location: login.php");
    exit();
}
?>