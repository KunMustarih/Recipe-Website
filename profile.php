<?php 
    session_start();

    $mysqli = require __DIR__ . "/database.php";

    $user_id = $_SESSION['user_id'];
    $grand_total = 0;
    $tax = 0;

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

    <div class="user-profile">
        <?php
          $select_user = mysqli_query($mysqli, "SELECT * FROM `user` WHERE id = '$user_id'") or die('query failed');
   
          if(mysqli_num_rows($select_user) > 0) {
            $user = $select_user->fetch_assoc();
          };
        ?>

        <p> username: <span><?php echo $user['name'];?></span></p>
        <p> email: <span><?php echo $user['email'];?></span></p>
        
    </div>

    <div class="order_history">
        <div class="box-container">
        <h1>Order history</h1>
        <table>
            <thead>
                <th>Name</th>
                <th>price</th>
                <th>Quantity</th>
                <th>Price</th>
            </thead>

            <?php 
       
            $select_order = mysqli_query($mysqli, "SELECT * FROM `order_info` WHERE user_id = '$user_id'") or die('Query failed');
            if(mysqli_num_rows($select_order) > 0)  {
                while($fetch_order_history = mysqli_fetch_assoc($select_order)) {
            ?>    
                <tr>
                    <td><?php echo $fetch_order_history['name']?></td>
                    <td><?php echo $fetch_order_history['price']?></td>
                    <td><?php echo $fetch_order_history['quantity']?></td>
                    <td>$<?php echo $sub_total = number_format($fetch_order_history['price'] * $fetch_order_history['quantity']); ?></td>
                </tr>
            <?php
                $grand_total += $sub_total;
                $tax = $grand_total * 0.05;        
                };
            }
            else {
                echo '<tr><td colspan="3"> no item bought</td></tr>';
            }
            ?>
            <tr class="table-bottom">
                <td>
                    <td colspan="2">Grand total with 5% Tax :</td>
                    <td>$<?php echo $grand_total + $tax; ?></td>
                </td>
            </tr>
        </table>
        </div>
    </div>
    <div>


</body>
</html>