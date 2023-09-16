

<?php

include './additionals/_database.php';

session_start();
// Unset token
unset($_SESSION['token']);
session_unset();
session_destroy();

header('location:index.html');

?>
