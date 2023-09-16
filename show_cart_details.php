<?php
require './startpage.php';
// require './additionals/startpage.php';
include './additionals/_database.php';


// Retrieve the user ID from the session
$userId = $_SESSION['email'];

// Retrieve cart details for the user from the database
$sql = "SELECT c.cart_id, b.book_name, b.author, b.price,b.book_img
        FROM shopping_cart c
        INNER JOIN books b ON c.book_id = b.book_id
        WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cart Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="gradient-background-2">
    <div class="mt-5 text-center display-1">
        <h1>Your Cart</h1>
    </div>
    <div class="container cart-container">


        <?php
        if (mysqli_num_rows($result) > 0) {
            $total=0;
            while ($row = mysqli_fetch_assoc($result)) {
                $total=$total+$row["price"];
                echo '
                <div class="cart-item">
                    <div class="row">
                        <div class="col-2">
                            <img src="./ebooksFolder/'.$row["book_img"].'" alt="' . $row["book_name"] . '">
                        </div>
                        <div class="col-6">
                            <h5 class="cart-item-title">' . $row["book_name"] . '</h5>
                            <p class="cart-item-author">' . $row["author"] . '</p>
                        </div>
                        <div class="col-2">
                            <p class="cart-item-price">₹' . $row["price"] . '</p>
                        </div>
                        <div class="col-2">
                        <button class="btn btn-danger btn-remove" data-cart-id="' . $row["cart_id"] . '">Remove</button>
    </div>
    </div>
    </div>

    ';
    }
    } else {
    echo '<p>Your cart is empty.</p>';
    }
    ?>
    </div>
    <div class="container">
        <form action="payment.php" method="POST">
            <div class="text-end">
                <input type="hidden" name="total_amount" value="<?php echo $total?>">
                <button id="checkout-btn" class="btn btn-primary" type="submit"
                    <?php echo mysqli_num_rows($result) > 0 ? '' : 'disabled'; ?>>
                    <span><i style='font-size:2rem;' class="fas fa-shopping-basket"></i></span>&nbsp Proceed to Checkout
                </button>

            </div>
        </form>
    </div>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script>
    $(document).ready(function() {


        // AJAX request when the "Remove" button is clicked
        $('.btn-remove').click(function() {
            var cartId = $(this).data('cart-id');

            // Send an AJAX request to remove the item from the cart
            $.ajax({
                url: 'remove_from_cart.php', // Replace with the URL of your PHP script to handle removal
                type: 'POST',
                data: {
                    cartId: cartId
                },

                success: function(response) {

                        // Reload the current page
                        location.reload();
                    },
                error: function() {
                    // An error occurred, show an error message
                    alert('Error occurred while sending the request.');
                }
            });
        });
    });
    </script>
</body>

</html>