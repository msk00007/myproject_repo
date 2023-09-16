<?php
session_start();
require_once './additionals/_database.php';
// Retrieve the user ID from the session
$userId = $_SESSION['email'];

// Retrieve cart details for the user from the database
$sql = "SELECT c.book_title
        FROM customer_orders c
        INNER JOIN books b ON c.book_title = b.book_name
        INNER JOIN shopping_cart s ON s.book_id = b.book_id
        WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0){
    echo'<script>alert("book is already in orders");
    window.location="./show_cart_details.php";</script>';
    
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Payment Gateway Clone</title>
    <!-- Include Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                  function placeOrder() {
        $.ajax({
            url: 'add_to_orders.php',
            type: 'POST',
            data: {
                action: 'checkout'
            },
            success: function(response) {
                // Handle the response from the server
                if (response) {
                    // Order placed successfully, show a success message
                    alert('Order placed successfully! it will processed by admin soon');
                    // Redirect the user to a thank-you page or order confirmation page
                    window.location.href = './show_cart_details.php';
                } else {
                    // An error occurred, show an error message
                    alert('Error occurred while placing order.');
                }
            },
            error: function() {
                // An error occurred, show an error message
                alert('Error occurred while placing the order.');
            }
        });
    }
            </script>
    <style>
    .container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        margin-top: 50px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    input[type=text],
    input[type=tel],
    input[type=email],
    textarea {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
    }

    input[type=text]:focus,
    input[type=tel]:focus,
    input[type=email]:focus,
    textarea:focus {
        border: 2px solid #555;
    }

    .box {
        background: rgb(0, 36, 4);
        background: linear-gradient(90deg, rgba(0, 36, 4, 1) 0%, rgba(9, 120, 121, 0.8071603641456583) 22%, rgba(80, 130, 215, 1) 75%, rgba(0, 212, 255, 1) 100%);
    }
    </style>

    <link href="style.css" rel="stylesheet">
</head>

<body class="box">
    <?php if(isset($_POST["total_amount"])) {
        if($_POST["total_amount"]==0){
           
            echo' <script>placeOrder();</script>';
            exit;
        }else{
        ?>
    <div class="container gradient-background-2">
        <h2 class="text-center mb-4">Payment Details</h2>
        <h3 class="text-center mb-4">total: ₹<?php echo $_POST["total_amount"]?>rs</h3>
        <form id="paymentForm">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="paymentMethod">Select Payment Method:</label>
                <select class="form-control" id="paymentMethod" name="paymentMethod" required>
                    <option value="none" default>select payment type</option>
                    <option value="card">Card Payment</option>
                    <option value="upi">UPI Payment</option>
                </select>
            </div>
            <div id="cardDetails" class="form-group" style="display: none;">
                <label for="cardNumber">Card Number:</label>
                <input type="text" class="form-control" id="cardNumber" name="cardNumber">
            </div>
            <div id="upiDetails" class="form-group" style="display: none;">
                <label for="upiId">UPI ID:</label>
                <input type="text" class="form-control" id="upiId" name="upiId">
            </div>
            <button type="submit" class="btn btn-primary">Pay Now</button>
            <button  class="btn btn-primary back">back</button>
        </form>
    </div>

    <?php }}
else{
    echo' <h2 class="text-center mb-4">no items selected</h2>';
}


require "./footer.html"
?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    function placeOrder() {
        $.ajax({
            url: 'add_to_orders.php',
            type: 'POST',
            data: {
                action: 'checkout'
            },
            success: function(response) {
                // Handle the response from the server
                if (response) {
                    // Order placed successfully, show a success message
                    alert('Order placed successfully! it will processed by admin soon');
                    // Redirect the user to a thank-you page or order confirmation page
                    window.location.href = './show_cart_details.php';
                } else {
                    // An error occurred, show an error message
                    alert('Error occurred while placing order.');
                }
            },
            error: function() {
                // An error occurred, show an error message
                alert('Error occurred while placing the order.');
            }
        });
    }



    const paymentMethodSelect = document.getElementById('paymentMethod');
    const cardDetails = document.getElementById('cardDetails');
    const upiDetails = document.getElementById('upiDetails');

    paymentMethodSelect.addEventListener('change', function() {
        const selectedPaymentMethod = paymentMethodSelect.value;
        if (selectedPaymentMethod === 'card') {
            cardDetails.style.display = 'block';
            upiDetails.style.display = 'none';
        } else if (selectedPaymentMethod === 'upi') {
            cardDetails.style.display = 'none';
            upiDetails.style.display = 'block';
        } else {
            cardDetails.style.display = 'none';
            upiDetails.style.display = 'none';
        }
    });


    document.getElementById('paymentForm').addEventListener('submit', function(event) {
        const paymentMethod = document.getElementById('paymentMethod').value;

        if (paymentMethod === 'card' && !isValidCardNumber(document.getElementById('cardNumber').value)) {
            event.preventDefault();
            alert('Please enter a valid card number.');
        } else if (paymentMethod === 'upi' && !isValidUpiId(document.getElementById('upiId').value)) {
            event.preventDefault();
            alert('Please enter a valid UPI ID.');
        } else if (paymentMethod === 'none') {
            event.preventDefault();
            alert('Please select a payment type');
        } else {
            // Call the placeOrder function when validation is successful
            placeOrder();
        }
    });

    // Card number validation function (simplified)
    function isValidCardNumber(cardNumber) {
        return /^\d{16}$/.test(cardNumber);
    }

    // UPI ID validation function (simplified)
    function isValidUpiId(upiId) {
        return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(upiId);
    }
    $(document).on('click',' .back',function(){
      window.location='./show_cart_details.php';
    });
    </script>

</body>

</html>