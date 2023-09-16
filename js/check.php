<script>// AJAX request when the "Proceed to Checkout" button is clicked
    $('#checkout-btn').click(function() {
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
                    alert('Order placed successfully!');
                    // // Redirect the user to a thank-you page or order confirmation page
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
    });
</script>