<!-- order.php -->
<?php
require './startpage.php';
// require './additionals/startpage.php';
include './additionals/_database.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Order Page</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./style.css">
  <style>
    .order_item{
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .book-title {
    flex: 1;
    max-width: 200px; /* Adjust this value based on your desired fixed width */
    word-wrap: break-word;
  }
  </style>
</head>
<body class="gradient-background-2">
  <h1>List of Books in Order of Purchase</h1>

  <?php
  $userid=$_SESSION['email'];
  // Query to retrieve the list of books in the order of their purchase time
  $sql = "SELECT order_id, book_title, author, purchase_time, purchase_status FROM customer_orders WHERE user_id ='$userid' ORDER BY purchase_time DESC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<div class='container'>";
    while ($row = $result->fetch_assoc()) {
      if($row['purchase_status']=="success"){
      $bookId = $row['order_id'];
      $title = $row['book_title'];
      $author = $row['author'];
      $purchaseTime = $row['purchase_time'];

      // Display book details and the "Join Chat" button
      echo "<div class='d-flex flex-row justify-content-between  flex-fill border border-info text-center p-2 m-2'>";
      echo "<h4 class='p-2 border book-title'>$title</h4>";
      echo "<p class='p-2 '>Author: $author</p>";
      echo "<p class='p-2 '>Purchase Time: $purchaseTime</p>";
      echo "<button class='btn btn-outline-primary join-chat-button' data-book-id='$title'>Join Chat</button>";
      
      echo "</div>";
      }
    }
    echo "</div>";
  } else {
    
    echo '<div class="display-1 mt-5 p-5 text-center text-dart">No books found.</div>';
  }

  $conn->close();
  ?>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
  <script>
      // Add an event listener to the "Join Chat" button
      $(document).on('click', '.join-chat-button', function() {
        // Extract the bookId from the button's data attribute
        var bookId = $(this).data('book-id');
        
        // Redirect the user to chat.php with the bookId as a query parameter
        window.location.href = './chatModule/chat.php?bookId=' + encodeURIComponent(bookId);
      });
    </script>
</body>
</html>
