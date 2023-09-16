<?php 
// require './additionals/startpage.php';
require './startpage.php';
require './additionals/_database.php';

// Increase maximum file size allowed for upload to 50MB
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');



$user_email = $_SESSION["email"];
$sql =  "SELECT * FROM userdetails WHERE email ='$user_email'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $user = $result->fetch_assoc();
}

  // Change the user details if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"] ?: $user["firstname"];
    $lastname = $_POST["lastname"] ?: $user["lastname"];
    $password = $_POST["password"] ?: $user["password"];
    $agegroup = $_POST["ageGroup"] ?: $user["agegroup"];

    // Check if the file was uploaded successfully
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $user_image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = './userProfiles/'.$user_image;
    } else {
        // File upload error occurred
        $user_image = $user['profilePic']; // Set a default value or handle the error accordingly
        $image_size = 0;
        $image_tmp_name = '';
        $image_folder = '';
    }

    $changeDetails = mysqli_query($conn, "UPDATE `userdetails` SET firstname = '$firstname', lastname='$lastname', password='$password', agegroup='$agegroup', profilePic= '$user_image' WHERE email = '$user_email'") or die('query failed');
    move_uploaded_file($image_tmp_name, $image_folder);

    if ($changeDetails) {
        if($user_image == 'useracount.jpg'){
            $alertMessage = 'profile pic not uploaded';
            $alertType = 'warning';
        }else{
        $alertMessage = 'changed successfully';
        $alertType = 'warning';
        }
    } else {
        $alertMessage = 'something went wrong';
        $alertType = 'warning';
    }
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>

<body class="gradient-background-3">
    <?php    // Display the removable alert if set
    if (isset($alertMessage) && isset($alertType)) {
        echo '
        <div class="alert alert-' . $alertType . ' alert-dismissible fade show mt-5 pt-3" role="alert">
            <strong>' . $alertMessage . '</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }?>

    <div class="container-fluid text-center d-flex flex-row justify-content-center mt-5 p-3 border-bottom border-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-square"
            viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
            <path
                d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z" />
        </svg>
        &nbsp
        <span class="h1">USER DETAILS</span>
    </div>

    <div id="popoverContent" class="border border-bg-primary popover-content gradient-background">
        <form class="form-control d-flex flex-column justify-content-center"  method="POST" enctype="multipart/form-data">
            <!-- Form fields -->
            <div>
                <label for="firstname">First name :</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter First Name">
            </div>
            <div>
                <label for="lastname">Last name :</label>
                <input type="text" id="lastname" name="lastname" placeholder="Enter Last Name">
            </div>
            <div>
                <label for="password">change password :</label>
                <input type="password" id="password" name="password" placeholder="Enter password">
            </div>
            <div>
                <label for="confirm-pass">confirm password :</label>
                <input type="password" id="confirm-pass" placeholder="confirm password">
            </div>
            <div>
                <select class="form-select" aria-label="Default select example" id="ageGroup" name="ageGroup">
                    <option selected value="teen">teenage(13-18)</option>
                    <option value="adult">adult(19-50)</option>
                    <option value="old">elderly(age>50)</option>
                </select>
            </div>
            <div>
                <label for="image">change profile pic :</label>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required>

            </div>
            <!-- Button to take action -->
            <button class="btn btn-outline-primary" id="actionButton">change</button>
        </form>
    </div>

    <?php
    if ($user) {
        echo '
        <div class="container text-center my-5">
            <div class="user-img "><img src="./userProfiles/'.$user["profilePic"].'" alt="user image"  class="img-fluid rounded-circle"></div>
            <button type="button" class="btn btn-outline-warning" id="popoverButton">Edit</button>

            <div class="row align-items-start mt-5">
                <div class="col-sm-6 border border text-bg-light border-primary my-2 text-primary p-2 h3">First name</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 p-2 h3">' . $user["firstname"] . '</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 text-primary p-2 h3">Last name</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 p-2 h3">' . $user["lastname"] . '</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 text-primary p-2 h3">Email</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 p-2 h3">' . $user["email"] . '</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 text-primary p-2 h3">Age group</div>
                <div class="col-sm-6 border border text-bg-light border-primary my-2 p-2 h3">' . $user["agegroup"] . '</div>
            </div>
        </div>';
    }


    ?>
  <?php include './footer.html'; ?>

<!-- script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>

<script>
    $(document).ready(function() {
        // Show popover on button click
        $('#popoverButton').click(function() {
            $('#popoverContent').toggle();
        });

        // Handle action button click
        $('#actionButton').click(function(e) {
            e.preventDefault();

            // Perform form validation
            if (!validateForm()) {
                return false;
            }

            // Submit the form
            $('form').submit();
        });

        function validateForm() {
            var firstName = document.getElementById("firstname").value;
            var lastName = document.getElementById("lastname").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm-pass").value;
            var ageGroup = document.getElementById("ageGroup").value;

            if (firstName === "" && lastName === "" && password === "" && ageGroup === "Select your age group") {
                alert("Please fill in at least one field to update.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Password and Confirm Password do not match.");
                return false;
            }

            return true;
        }
    });
</script>

</body>

</html>
