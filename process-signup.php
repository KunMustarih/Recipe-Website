<?php
if (empty($_POST['firstName'])) {
    die('Name is required');
}

if ( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die('Valid email is required');
}

if (! preg_match("/[a-z]/i", $_POST['password'])) {
    die('Password must contain at least one letter');
}

if (! preg_match("/[0-9]/", $_POST['password'])) {
    die('Password must contain at least one number');
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$fullName = $_POST['firstName'] .' '. $_POST['lastName'];

$stmt->bind_param("sss",
                   $fullName,
                   $_POST['email'],
                   $password_hash);

try {
    if ($stmt->execute()) {
        header('Location: signup-success.html');
        exit;
    } else {
        die("Error during execution: " . $stmt->error . " " . $stmt->errno);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        // Duplicate entry error
         header('Location: signup-failed.html');
    } else {
        die("General SQL exception: " . $e->getMessage() . " " . $e->getCode());
    }
}
?>