<?php
$email = "";
$errors = array();
    if($_SERVER["REQUEST_METHOD"]=="POST"){

        include './additionals/_database.php';
        $firstname = $_POST["First_name"];
        $lastname = $_POST["Last_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $gender = $_POST["gender"];
        $agegroup = $_POST["agegroup"];
    
        $select_users = mysqli_query($conn, "SELECT * FROM `userdetails` WHERE email = '$email'") or die('query failed');

        if(mysqli_num_rows($select_users) > 0){
            $errors['email'] = "Email that you have entered is already exist!";
        }

            if(count($errors) === 0){
                $encpass = password_hash($password, PASSWORD_DEFAULT);
                $code = rand(999999, 111111);
                $status = "notverified";
                $insert_data = "INSERT INTO `userdetails` (`firstname`, `lastname`, `email`, `password`, `gender`, `agegroup`, `code`, `user_status`) VALUES ('$firstname', '$lastname', '$email','$encpass', '$gender', '$agegroup', '$code', '$status')";
                $data_check = mysqli_query($conn, $insert_data);
                if($data_check){
                    $subject = "Email Verification Code";
                    $message = "Your verification code is $code";
                    $sender_email = "machha394@gmail.com";


                    $sendmail_url = "http://localhost/myproject2/sendmail.php";
                    $params = [
                        'email' => $email,
                        'code' => $message
                    ];
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $sendmail_url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                    $response = curl_exec($ch);
                    
                    curl_close($ch);

                    if ($response) {
                        $info = "We've sent a verification code to your email - $email";
                        session_start();
                        $_SESSION['info'] = $info;
                        $_SESSION['email']=$email;
                        $_SESSION['password'] = $password;
                        header('location: user-otp.php');
                        exit();
                    }else{
                        $errors['otp-error'] = "Failed while sending code!";
                    }
                }else{
                    $errors['db-error'] = "Failed while inserting data into database!";
                }
            }
      }

    
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
    <link rel="stylesheet" href="signstyle.css">
</head>

<body class="gradient-background">

    <div class="h1 text-center mt-3">Sign-up</div>
    <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
    <form method="POST" onsubmit="return validateForm()" autocomplete="">

        <div class="container">
            
            <div class="row g-3">
                <div class="col">
                    <input type="text" class="form-control" placeholder="First name" aria-label="First name"
                        id="First_name" name="First_name" />
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Last name" aria-label="Last name"
                        id="Last_name" name="Last_name" />
                </div>
            </div>
            <br>
            <div>
                <input class="form-control form-control-lg" type="text" placeholder="Email Addresss"
                    aria-label=".form-control-lg example" id="email" name="email">
            </div>
            <br>
            <div class="align-items-center">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Password</label>
                    </div>

                    <div class="col-auto">
                        <input type="password" id="password" name="password" class="form-control"
                            aria-labelledby="passwordHelpInline" />
                    </div>
                    <div class="col-auto">
                        <span id="passwordHelpInline" class="form-text">
                            Must be 8-20 characters long.
                        </span>
                    </div>
                </div>

                <br>

                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="confirm-password" class="col-form-label">Confirm-Password</label>
                    </div>

                    <div class="col-auto">
                        <input type="password" id="confirm-password" class="form-control" />
                    </div>
                </div>
                <br>


                <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male" checked />
                            <label class="form-check-label" for="male">
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female" />
                            <label class="form-check-label" for="female">
                                Female
                            </label>
                        </div>
                        <div class="form-check disabled">
                            <input class="form-check-input" type="radio" name="gender" id="others" value="Others" />
                            <label class="form-check-label" for="others">
                                Others
                            </label>
                        </div>
                    </div>
                </fieldset>

                <select class="form-select" aria-label="Default select example" id="agegroup" name="agegroup">
                    <option selected>Select your age group</option>
                    <option value="teen">teenage(13-18)</option>
                    <option value="adult">adult(19-50)</option>
                    <option value="old">elderly(age>50)</option>
                </select>
                <br>
                <div>
                    <button type="submit" class="btn btn-primary" onclick="return validateForm()">Submit</button>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    <a href="loginn.php" class="btn btn-primary">Login</a>
                </div>
    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <script>
    function validateForm() {
        var firstName = document.getElementById("First_name").value;
        var lastName = document.getElementById("Last_name").value;
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm-password").value;
        var ageGroup = document.getElementById("agegroup").value;

        // Validate first name
        if (firstName.trim() === "") {
            alert("Please enter your first name");
            return false;
        }

        // Validate last name
        if (lastName.trim() === "") {
            alert("Please enter your last name");
            return false;
        }

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

        // Validate confirm password
        if (confirmPassword === "") {
            alert("Please confirm your password");
            return false;
        } else if (password !== confirmPassword) {
            alert("Passwords do not match");
            return false;
        }

        // Validate age group
        if (ageGroup === "Select your age group") {
            alert("Please select your age group");
            return false;
        }
        return true;
    }

    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    </script>

</body>

</html>