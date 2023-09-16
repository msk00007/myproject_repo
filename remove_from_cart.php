<?php
session_start();
require './additionals/_database.php';

if (!isset($_SESSION['email'])) {
    header('Location: loginn.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartId'])) {
    $cartId = $_POST['cartId'];
    $userId = $_SESSION['email'];

    // Check if the item belongs to the user
    $checkSql = "SELECT user_id FROM shopping_cart WHERE cart_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, 'i', $cartId);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if (!$checkRow || $checkRow['user_id'] !== $userId) {
        // The item does not belong to the user or does not exist
        $response = ['success' => false];
    } else {
        // Item belongs to the user, proceed with removal
        $removeSql = "DELETE FROM shopping_cart WHERE cart_id = ?";
        $removeStmt = mysqli_prepare($conn, $removeSql);
        mysqli_stmt_bind_param($removeStmt, 'i', $cartId);
        $result = mysqli_stmt_execute($removeStmt);

        if ($result) {
            http_response_code(200);
    $response = array('success' => true);
        } else {
            http_response_code(500);
            $response = array('success' => false);
        }
    }
} else {
    // Invalid request
    http_response_code(400);
    $response = array('success' => false);
}

// Return a JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
