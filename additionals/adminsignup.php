<?php
require '../additionals/_database.php';
$password="Saikrishna1";
$adminname ="Saikrishna";
$adminmail="machha394@gmail.com";
$password=password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO `admin_details` (`admin_name`, `admin_mail`, `password`) VALUES('$adminname', '$adminmail', '$password')";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result) > 0){echo'success';};