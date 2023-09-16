<?php
require '../additionals/_database.php';
session_start();
if(!isset($_SESSION)){
  session_destroy( );
  header("Location:index.html");
}
// Get the message, current user, book ID, and reply message ID from the request
$message = $_POST['message'];
$currentUserId = $_SESSION['email'];
$currentUser = $_POST['currentUser'];
$bookId = $_POST['bookId'];
$replyMessageId = $_POST['replyMessageId'];

// Retrieve the sender and recipient of the replied message
$replySender = '';
$replyRecipient = '';
if (!empty($replyMessageId)) {
  $sql = "SELECT sender, recipient FROM chat_messages WHERE msg_id = $replyMessageId";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $replySender = $row['sender'];
    $replyRecipient = $row['recipient'];
  }
}

// Insert the new message into the chat_messages table
$sql = "INSERT INTO chat_messages (sender_id, sender, recipient, text, timestamp, book_name, reply_to) VALUES (?, ?, ?, ?, NOW(), ?, ?)";

// Prepare and bind the parameters 
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $currentUserId, $currentUser, $replySender, $message, $bookId, $replyMessageId);

if ($stmt->execute()) {

  // Message sent successfully
  echo "Message sent";
} else {
  // Error in sending the message
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
