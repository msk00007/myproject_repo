<?php
require '../additionals/_database.php';

// Get the book ID from the request
$bookId = $_GET['bookId'];

// Retrieve chat messages for the selected book
$sql = "SELECT msg_id, sender_id, sender, text, timestamp, reply_to
        FROM chat_messages
        WHERE book_name = '$bookId'
        ORDER BY timestamp ASC";

$result = $conn->query($sql);

$messages = array();

if ($result->num_rows > 0) {
    // Fetch chat messages and add them to the array
    while ($row = $result->fetch_assoc()) {
        $message = array(
            'id' => $row['msg_id'],
            'sender' => $row['sender'],
            'senderId' => $row['sender_id'],
            'text' => $row['text'],
            'timestamp' => $row['timestamp'],
            'replyTo' => null
        );

        // Check if the message is a reply
        if ($row['reply_to']) {
            $replyMessage = getReplyMessage($conn, $row['reply_to']);
            if ($replyMessage) {
                $message['replyTo'] = "{$replyMessage['sender']}:\n {$replyMessage['text']}";
            }
        }

        $messages[] = $message;
    }
}

// Return chat messages as JSON
echo json_encode($messages);

// Function to retrieve the reply message by ID
function getReplyMessage($conn, $messageId) {
    $sql = "SELECT sender, text FROM chat_messages WHERE msg_id = '$messageId'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}
?>
