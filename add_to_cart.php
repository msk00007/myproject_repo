<?php
include './additionals/_database.php';

session_start();

// Function to sanitize input
function sanitize($value)
{
    return htmlspecialchars(trim($value));
}

// Retrieve the book ID from the AJAX request and convert it to an integer
$bookId = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

// Retrieve the user ID from the session
$userId = $_SESSION['email'];

// Retrieve the book details from the database using the book ID
$sql = "SELECT * FROM books WHERE book_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $bookId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);

// Check if the book exists
if (!$book) {
    // Return a failure response
    $response = array('success' => false);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Check if the book is already in the shopping cart for the user
$sql = "SELECT * FROM shopping_cart WHERE book_id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'is', $bookId, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$addedProduct = mysqli_fetch_assoc($result);

// If the book is already in the cart, return a failure response
if ($addedProduct) {
    $response = array('success' => false);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Insert the cart details into the database within a single transaction
mysqli_begin_transaction($conn);

try {
    // Insert the cart details into the database
    $price = $book['price'];
    $insertSql = "INSERT INTO shopping_cart (user_id, book_id, price) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertSql);
    mysqli_stmt_bind_param($insertStmt, 'sis', $userId, $bookId, $price);
    mysqli_stmt_execute($insertStmt);

    // Commit the transaction if everything is successful
    mysqli_commit($conn);

    // Return a success response
    $response = array('success' => true);
} catch (Exception $e) {
    // If an error occurs, roll back the transaction
    mysqli_rollback($conn);

    // Return a failure response
    $response = array('success' => false);
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
