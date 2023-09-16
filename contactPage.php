<?php require "./startpage.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="./style.css" rel="stylesheet">
</head>
<body class="gradient-background-3">
    <div class="container">
        <h1 class="text-center text-warning mt-5 p-3">Contact Us</h1>
        <div class="contact_box d-sm-flex flex-row flex-md-row p-2 justify-content-around align-items-center">
            <div>
                <img src="./additionals/contactUs.jpg.png" alt="contact img m-2">
            </div>
            <div>
                <form id="contactForm" class="form-floating m-2" action="" method="POST">
                    <textarea class="form-control" name="message" id="message" cols="40" rows="6" placeholder="Message"></textarea><br />
                    <label for="message">Write your Query</label>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
                <div class="h4 text-light m-2">(or)</div>
                <div class="h3 text-warning m-2">Call us</div>
                <div class="h4 text-light text-decoration-underline m-2">8885248244</div>
            </div>
        </div>
    </div>

    <?php require "./footer.html"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Submit form using AJAX
            $('#contactForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission
                var message = $("#message").val();
                if (message.trim() !== "") {
                $.ajax({
                    type: 'POST',
                    url: 'customer_send_message.php', // Replace with your PHP script's URL
                    data: { message: message },
                success: function(response) {
                    // Handle the response from the server
                    alert(response); // You can replace this with your own logic to display success or error messages
                    $("#message").val(""); // Clear the textarea after sending
                }
            });
        } else {
            alert("Message cannot be empty!");
        }
    });
            });
    </script>
</body>
</html>
