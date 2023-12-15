<?php
    $mysqli = require __DIR__ . "/database.php";

    $grand_total = 0;
    $tax = 0;

    session_start();

    $user_id = $_SESSION['user_id'];
   

    if(!isset($user_id)){
        header('Location:login.php');
    }

    if(isset($_GET['logout'])) {
        unset($user_id);
        session_destroy();
        header('location:login.php');
    }
    $message = []; 

    if (isset($_POST['add_to_cart'])) {
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_quantity = $_POST['product_quantity'];

        $select_cart = mysqli_prepare($mysqli, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        mysqli_stmt_bind_param($select_cart, 'ss', $product_name, $user_id);
        mysqli_stmt_execute($select_cart);
        $result = mysqli_stmt_get_result($select_cart);

        if(mysqli_num_rows($result) > 0) {
            $message [] = "Product already in the cart";
        }
        else {
            mysqli_query($mysqli,"INSERT INTO `CART`(user_id, name, price, quantity) VALUES 
            ('$user_id','$product_name', '$product_price', '$product_quantity')
            ") or die('query failed');
            $message [] = 'product added to cart!';
        }
    }


    if(isset($_POST['update_cart'])) {
        $update_quantity = $_POST['cart_quantity'];
        $update_id = $_POST['cart_id'];
        $update_query = mysqli_prepare($mysqli, "UPDATE `cart` SET  quantity = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_query, 'ss', $update_quantity, $update_id);
        mysqli_stmt_execute($update_query);
        $message [] = 'cart quantity updated successfully!';

    }

    if(isset($_GET['remove'])) {
        $delete_all_query = mysqli_prepare($mysqli, "DELETE FROM `cart` WHERE id = ?");
        mysqli_stmt_bind_param($delete_all_query, 's', $remove_id);
        mysqli_stmt_execute($delete_all_query); 
        header('location:buy-book.php');
    }

    if(isset($_GET['delete_all'])) { 
        $delete_all_query = mysqli_prepare($mysqli, "DELETE FROM `cart` WHERE user_id = ?");
        mysqli_stmt_bind_param($delete_all_query, 's', $user_id);
        mysqli_stmt_execute($delete_all_query);
        header('location:buy-book.php');
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

    <div class="messages">
        <?php
        // Display messages
        foreach ($message as $msg) {
            echo "<p>$msg</p>";
        }
        ?>
    </div>

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
    <div class="products">
        <div class="box-container">
        <?php 
            $mysqli = require __DIR__ . "/database.php";
            $select_product = mysqli_query($mysqli, "SELECT * FROM `products`") or die('Query failed');
            if(mysqli_num_rows($select_product) > 0)  {
                while($fetch_product = mysqli_fetch_assoc($select_product)) {
        ?>

            <form method="post" class="box" action="">
                <div class="name"><?php echo $fetch_product['name']; ?></div>
                <div class="price"><?php echo $fetch_product['price']; ?></div>
                <input type="number" min="1" name="product_quantity" value="1">
                <input type="hidden" name="product_name" value= "<?php echo $fetch_product['name'];?>">
                <input type="hidden" name="product_price" value= "<?php echo $fetch_product['price'];?>">
                <input type="submit" value="add to cart" name="add_to_cart" class="btn">
            </form>
            <?php
                };
            };
            ?>
        </div>
    </div>
    <div class="shopping-cart">
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
                <td>$<?php echo $grand_total + $tax; ?></td>
                <td><a href="buy-book.php?delete_all" onclick="return confirm ('delete all from cart?')";" class="delete-btn cart-btn <?php echo ($grand_total > 1)? '': 'disabled'; ?>">delete all</a></td>
            </tr>
        </table>

        <div class="cart-btn">
            <button class="checkout-btn <?php echo ($grand_total > 1)? '': 'disabled'; ?>"><a href="Checkout.php">CHECKOUT</a></button>
        </div>
    </div>
    
</body>
</html>