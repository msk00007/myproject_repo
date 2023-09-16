<?php 
include '../myproject1/additionals/_database.php';
$createChatMessagesTable = "CREATE TABLE donate (
    bookid INT PRIMARY KEY AUTO_INCREMENT,
    user_email VARCHAR(255),
    book_title VARCHAR(255),
    book_author VARCHAR(255),
    book_genre VARCHAR(255),
    book_description TEXT,
    book_image VARCHAR(255),
    book_pdf VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    FOREIGN KEY (user_email) REFERENCES userdetails(email)
)
";
if ($conn->query($createChatMessagesTable) === TRUE) {
    echo "Table 'chat_messages' created successfully.<br>";
} else {
    echo "Error creating table 'chat_messages': " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>