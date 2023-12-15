<?php
session_start();
$is_invalid = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";
    
    // Using prepared statement to prevent SQL injection
    $email = $mysqli->real_escape_string($_POST["email"]);
    $sql = "SELECT * FROM user WHERE email = ?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($_POST['password'], $user["password_hash"])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: Home.html');
        exit;
    }

    $is_invalid = true;

    // Close the statement
    $stmt->close();
}
?>

 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sharedstyle.css">
    <link rel="stylesheet" href="Desktop.css" media="screen and (min-width: 1024px)">
    <link rel="stylesheet" href="Laptop.css" media="screen and (min-width: 768px) and (max-width: 1023px)">
    <link rel="stylesheet" href="Phone.css" media="screen and (max-width: 767px)">
    <title>Document</title>
</head>
<body>

    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>

     <div class="login-container">
        <h2>Login</h2>
        <form id="login-form" method="post" >
            <div class="form-group">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="email"  value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                <div class="error"></div>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password">
                <div class="error"></div>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit" id='login-button'>
            </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>