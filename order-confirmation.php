<?php
$mysqli = require __DIR__ . "/database.php";

session_start();

$user_id = $_SESSION['user_id'];
$card_number = 00000;
$address = "Kirksville";

$name = '';
$price = 0;
$quantity = 0;

$cart_query = mysqli_prepare($mysqli, "SELECT * FROM `cart` WHERE user_id = ?");
mysqli_stmt_bind_param($cart_query, 's', $user_id);
mysqli_stmt_execute($cart_query);
$result = mysqli_stmt_get_result($cart_query);
while ($fetch_cart = mysqli_fetch_assoc($result)) {
    if ($fetch_cart['user_id'] === $user_id) {
                $name = $fetch_cart['name'];
                $price = $fetch_cart['price'];
                $quantity = $fetch_cart['quantity'];

                $order_query = mysqli_prepare($mysqli, "INSERT INTO `order_info` (user_id, credit_card, address, name, price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($order_query, 'ssssss', $user_id, $card_number, $address, $name, $price, $quantity);
                mysqli_stmt_execute($order_query);
        }
    }
    mysqli_query($mysqli,"DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        
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
    <title>Home | Lazeez</title>
</head>
<body>
    <header>
        <h1>Lazeez</h1>
    </header>
    <nav class="navigation">
        <ul>
            <li><a href="Home.html">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="recipe-search.html">Recipe Search</a></li>
            <li><a href="buy-book.php">Buy Recipe Book</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="terms.html">Terms and Services</a></li>
            <li><a href="Index.html">Logout</a></li>
        </ul>
    </nav>

    <h1>Your order has been placed. Thank you for shopping with us</h1>

</body>
</html>
