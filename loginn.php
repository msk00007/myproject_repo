<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: ./home.php');
    exit;
}
?>
<!--  -->
<!DOCTYPE html>

<html>

<head>
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="signstyle.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      var imageIndex = 0;
      var images = ["./additionals/view.png", "./additionals/eye.png"]; 

      function toggleImage() {
        if (imageIndex === 0) {
          imageIndex = 1;
          $(".change").attr("type", "text");
        } else {
          imageIndex = 0;
          $(".change").attr("type", "password");
        }


        $("img").attr("src", images[imageIndex]);
      }

      $("#passview").click(function(){
        toggleImage(); 
      });
    });
  </script>

</head>

<body class=gradient-background>

    <h1 class="center">Sign In</h1>
    <div class="sign-in-form">

        <form method="POST" onsubmit="return validateForm()">
          <input type="text" name="email" id="email" placeholder="email">
          <input type="password" class="change" name="password" id="password" placeholder="Password">
          <button type="button" class="btn btn-primary-outline" id="passview"><img src="./additionals/view.png" alt="" width="15px" height="15px"></button>
          <div><label for="Account_type">Select Accout type</label>
          <select name="Account_type" id="Account_type" placeholder="select account_type">
            <option value="user">user</option>
            <option value="Admin">Admin</option>
          </select>
          </div>
            <div class="center">
                <button type="submit" class="sign-btn" value="Sign In" onclick="return validateForm()">Sign in</button>
            </div>
        </form>
    </div>
    <div style="text-align:center">
        <span>new user ! </span><a href="./registration.php"><strong>click here to register</strong></a>
    </div>
    <div style="text-align:center">
        <a href="./forgot-password.php"><strong>forgot password</strong></a>
    </div>

    <script>
    function validateForm() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        // Validate email
        if (email.trim() === "") {
            alert("Please enter your email address");
            return false;
        } else if (!validateEmail(email)) {
            alert("Please enter a valid email address");
            return false;
        }

        // Validate password
        if (password === "") {
            alert("Please enter a password");
            return false;
        } else if (password.length < 8 || password.length > 20) {
            alert("Password must be 8-20 characters long");
            return false;
        }
        return true;

    }

    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>

</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include './additionals/_database.php';
    $email = $_POST["email"];
    $password = $_POST["password"];
    $account_type = $_POST["Account_type"];

    $tableName = ($account_type === "user") ? "userdetails" : "admin_details";
    $columnEmail = ($account_type === "user") ? "email" : "admin_mail";
    $columnPassword = ($account_type === "user") ? "password" : "password";

    $query = "SELECT * FROM $tableName WHERE $columnEmail = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user = $result->fetch_assoc();
        $userpassword =$user["password"];

        if (password_verify($password, $userpassword)) {
            // After successful login
            session_start();
            session_regenerate_id();
            $_SESSION['loggedIn'] = true;

            if ($account_type === "user") {
                $status = $user['user_status'];
                if($status == 'verified'){
                $_SESSION["user_id"] = $user["firstname"];
                $_SESSION["email"] = $user["email"];
                header("Location: home.php");
                }else{
                    $info = "It's look like you haven't still verify your email - $email";
                    $_SESSION['info'] = $info;
                    header('location: user-otp.php');
                }
            } else {
                $_SESSION["user_id"] = $user["admin_name"];
                $_SESSION["email"] = $user["admin_mail"];
                header("Location: ./adminfolder/admin_page.php");
            }
        }else{
            echo '
            <div class="alert alert-dismissible fade show mt-5 pt-3 d-flex justify-content-center" role="alert">
                <strong>Log in failed !</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        }
     else {
        die("error");
     }

}
?>
