<?php 
    $mysqli = require __DIR__ . "/database.php";

    $grand_total = 0;
    $tax = 0;

    session_start();

    $user_id = $_SESSION['user_id'];

     if(!isset($user_id)){
        header('Location:login.php');
    }

    if(isset($_POST['update_cart'])) {
        $update_quantity = $_POST['cart_quantity'];
        $update_id = $_POST['cart_id'];
        $update_query = mysqli_prepare($mysqli, "UPDATE `cart` SET quantity = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_query, 'ss', $update_quantity, $update_id);
        mysqli_stmt_execute($update_query);
        $message [] = 'cart quantity updated successfully!';

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
    <script src="https://www.paypal.com/sdk/js?client-id=AcQc0h4fPXMZoFdIJ7L9k6k7Oekp40WsXqhE0OMtqzgCn_GqZ_d8QrG0uE1oAnl9F_dOYQI473dbO2yU"></script>
    <title>Home | Lazeez</title>
</head>
<body>
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
    <h1 class="heading">Shopping Cart</h1>

        <table>
            <thead>
                <th>Name</th>
                <th>price</th>
                <th>quantity</th>
                <th>total price</th>
                <th>action</th>
            </thead>

            <?php 
            $cart_query = mysqli_query($mysqli, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
            if(mysqli_num_rows($cart_query) > 0)  {
                while($fetch_cart = mysqli_fetch_assoc($cart_query)) {
            ?>
                <tr>
                    <td><?php echo $fetch_cart['name']; ?></td>
                    <td><?php echo $fetch_cart['price']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                            <input type="submit" name="update_cart" value="update" class="option-btn">
                        </form>
                    </td>
                    <td>$<?php echo $sub_total = number_format($fetch_cart['price'] * $fetch_cart['quantity']); ?></td>
                    <td><a href="buy-book.php?remove=<?php echo $fetch_cart['id'];?>" class="delete-btn" onclick="return confirm('remove item from cart?');">remove</a></td>
                </tr>
            <?php
                $grand_total += $sub_total;
                $tax = $grand_total * 0.05;
                };
            }
            
            else {
                echo '<tr><td colspan="6"> no item added</td></tr>';
                $grand_total = 0;
            }
            ?>
            <tr>
                <td colspan= "3">Tax added</td>
                <td><?php echo "$". $tax?></td>
            </tr>
            <tr class="table-bottom">
                <td colspan="3">Grand total :</td>
                <td><?php echo $grand_total + $tax; ?></td>
                <td><a href="buy-book.php?delete_all" onclick="return confirm ('delete all from cart?')";" class="delete-btn">delete all</a></td>
            </tr>
        </table>        

        <div id="paypal-button-container" class="paypal-button" ></div>

        <script src="script.js"></script>
        <script>
        //Paypal checkout
            paypal.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: <?php echo $grand_total + $tax; ?>
                            }
                        }]
                    });
                },
                onApprove:function(data,actions) {
                    return actions.order.capture().then(function(details){
                        if (details.status === 'COMPLETED') {
                            // Payment is complete, redirect to another page
                            window.location.href = 'order-confirmation.php';
                        }
                        else {
                            alert('Payment not completed successfully.');
                            }
                    })
                }
            }).render('#paypal-button-container')  
        </script>
</body>
</html>
