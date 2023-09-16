<?php
    session_start();
     require "./additionals/_database.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["message"])) {
        $message = $_POST["message"];
       
        $sql = "INSERT INTO customer_messages (user_id, message) VALUES ('".$_SESSION['email']."', '".$message."')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "Message sent successfully!";
        }
        // echo "success";
    } else {
        echo "Message cannot be empty!";
    }
    exit;
?>
