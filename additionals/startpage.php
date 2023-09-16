<?php
session_start();

// Check if the user is not logged in or the token is invalid
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Redirect to the login page or perform other actions
    header('Location: index.html');
    exit;
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>

        <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom  fixed-top" data-bs-theme="dark">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">welcome</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./home.php">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">help</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Dashboard
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="C:/xampp/htdocs/myproject1/profile.php">profile</a></li>
                      <li><a class="dropdown-item" href="C:/xampp/htdocs/myproject1/customer_order.php">myorders</a></li>
                      <li><a class="dropdown-item" href="C:/xampp/htdocs/myproject1/show_cart_details.php">view cart</a></li>
                      <li><a class="dropdown-item" href="C:/xampp/htdocs/myproject1/logout.php">logout</a></li>
                    </ul>

                  </li>

                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Explore
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="C:/xampp/htdocs/myproject1/ebooks.php">eBooks</a></li>
                      <li><a class="dropdown-item" href="#">Journals</a></li>
                      <li><a class="dropdown-item" href="#">Articles</a></li>
                      <li><a class="dropdown-item" href="#">Donate</a></li>
                    </ul>

                  </li>
                </ul>
         
                <div class="d-flex flex-end"><h4 style="color:white"><?php
                  session_regenerate_id();
                  
                  echo 'welcome ' .$_SESSION["user_id"];
                ?></h4>
                </div>
           
              </div>
            </div>
          </nav>
</body>
</html>