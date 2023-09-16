<?php
session_start();


if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {

    header('Location: index.html');
    exit;
}?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Admin portal</title>
</head>

<body>
    <div class="px-3 py-3 text-bg-dark border-bottom my-3 ">
        <div class="container-fluid">

            <div class="d-flex flex-wrap  justify-content-between">
            <div class="display-5 text-info col-4">
                Admin-Panel
            </div> 
            <ul class="nav col-12 col-lg-auto my-2 justify-content-between my-md-0 text-medium h3">
                    <li>
                        <a href="./admin_page.php" class="nav-link text-white">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="./admin_order.php" class="nav-link  text-white">
                            Orders
                        </a>
                    </li>
                    <li>
                        <a href="./admin_products.php" class="nav-link text-white">
                            </svg>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="./admin_customer_details.php" class="nav-link  text-white">
                            Customers
                        </a>
                    </li>
                    <li>
                        <a href="./admin_customer_messages.php" class="nav-link  text-white">
                            Messages
                        </a>
                    </li>
                </ul>
                <div class="d-flex flex-fill justify-content-end display-6 text-info">
                <a href="../logout.php" class="text-white">Log out</a>
                </div>
                <div class="d-flex flex-fill justify-content-end display-4 text-info">
                
                <?php 
                    
                      session_regenerate_id();
                      echo $_SESSION["user_id"];
                      ?>
                </div>

            </div>
        </div>
    </div>


</body>

</html>