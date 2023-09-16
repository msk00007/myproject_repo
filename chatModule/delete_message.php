<?php
require '../additionals/_database.php';

$messageId = $_POST['messageId'];

// Delete the chat message from the database
$sql = "DELETE FROM chat_messages WHERE msg_id = '$messageId'";
$result = $conn->query($sql);

// Check if the deletion was successful
if ($result) {
    echo 'Message deleted successfully';
} else {
    echo 'Failed to delete message';
}
?>
