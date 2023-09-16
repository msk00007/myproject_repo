<?php
include './additionals/_database.php';
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // User is not logged in, handle the scenario accordingly (e.g., return an error message)
  http_response_code(401);
  echo "User not logged in";
  exit();
}

// Check if the AJAX request contains the action 'checkout'
if (isset($_POST['action']) && $_POST['action'] === 'checkout') {
  // Retrieve the user ID from the session
  $userId = $_SESSION['email'];

  // Retrieve cart details for the user from the database (similar to cart.php)
  $sql = "SELECT c.cart_id, b.book_name, b.author
          FROM shopping_cart c
          INNER JOIN books b ON c.book_id = b.book_id
          WHERE c.user_id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 's', $userId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Prepare the data to insert into the customer_orders table
  $orderItems = array();

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      // Add book details to the orderItems array
      $orderItems[] = array(
        'book_title' => $row['book_name'],
        'author' => $row['author'],
        'user_id' => $userId
      );
    }
    
  }

  // Start a transaction
  mysqli_begin_transaction($conn);

  try {
    // Insert order items into the customer_orders table
    $insertSql = "INSERT INTO customer_orders (book_title, author, user_id) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertSql);

    foreach ($orderItems as $item) {
      $bookTitle = $item['book_title'];
      $author = $item['author'];
      $userId = $item['user_id'];

      // Insert the order into the customer_orders table
      mysqli_stmt_bind_param($insertStmt, 'sss', $bookTitle, $author, $userId);
      mysqli_stmt_execute($insertStmt);
    }

    // Delete items from the shopping cart
    $deleteSql = "DELETE FROM shopping_cart WHERE user_id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, 's', $userId);
    mysqli_stmt_execute($deleteStmt);

    // Commit the transaction if everything is successful
    mysqli_commit($conn);

    // If all orders are inserted and items deleted successfully, return a success response
    http_response_code(200);
    $response = array('success' => true);
  } catch (Exception $e) {
    // If an error occurs, roll back the transaction
    mysqli_rollback($conn);

    // Return an error message
    http_response_code(500);
    $response = array('success' => false);
  } finally {
    // Close the statements
    mysqli_stmt_close($insertStmt);
    mysqli_stmt_close($deleteStmt);
  }
} else {
  // If the AJAX request does not contain the action 'checkout', return an error message
  http_response_code(400);
  $response = array('success' => false);
}

// Output the response as JSON
echo json_encode($response);
