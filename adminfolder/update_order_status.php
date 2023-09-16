<?php
require '../additionals/_database.php';

if (isset($_POST['orderId'])) {
  $orderId = $_POST['orderId'];
  
  // Update the purchase status in the database
  $updateSql = "UPDATE customer_orders SET purchase_status = 'success' WHERE order_id = $orderId";
  if ($conn->query($updateSql) === TRUE) {
    echo 'success';
  } else {
    echo 'error';
  }
} else {
  echo 'error';
}
?>
