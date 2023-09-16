<?php

include '../additionals/_database.php';
include './admin_header.php';
if(!isset($_SESSION["user_id"])){
   header('location:index.html');
};

require '../additionals/_database.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookTitle = sanitizeInput($_POST["bookTitle"]);
    $bookAuthor = sanitizeInput($_POST["bookAuthor"]);
    $bookGenre = sanitizeInput($_POST["bookGenre"]);
    $bookType = sanitizeInput($_POST["bookType"]);
    $bookDescription = sanitizeInput($_POST["bookDescription"]);
    $bookPrice = sanitizeInput($_POST["bookPrice"]);
    $bookRating = 0;
    $userEmail = $_SESSION["email"];

    $select_product_name = mysqli_query($conn, "SELECT book_name FROM `books` WHERE book_name = '$bookTitle'") or die('query failed 1');
    if(mysqli_num_rows($select_product_name) > 0){
        // message
        echo '
        <div class="alert alert-warning alert-dismissible fade show mt-5 pt-3" role="alert">
            <strong>book already exists</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
       }else{
    // Handle image upload
    $targetImageDir = "../ebooksFolder/";
    $targetImageFile = $targetImageDir . basename($_FILES["bookImage"]["name"]);
    $uploadImageOk = 1;
    $imageFileType = strtolower(pathinfo($targetImageFile, PATHINFO_EXTENSION));

    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo "Only JPG, JPEG, PNG, and GIF images are allowed.";
        $uploadImageOk = 0;
    }

    if ($uploadImageOk == 0) {
        echo "Sorry, your image was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["bookImage"]["tmp_name"], $targetImageFile)) {
            $imagePath = $conn->real_escape_string($targetImageFile);

// Handle DOCX file upload
$targetDOCXDir = "../ebooksFolder/";
$targetDOCXFile = $targetDOCXDir . basename($_FILES["bookDOCX"]["name"]);
$uploadDOCXOk = 1;
$docxFileType = strtolower(pathinfo($targetDOCXFile, PATHINFO_EXTENSION));

if ($docxFileType != "docx") {
    echo "Only DOCX files are allowed.";
    $uploadDOCXOk = 0;
}

if ($uploadDOCXOk == 0) {
    echo "Sorry, your DOCX file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["bookDOCX"]["tmp_name"], $targetDOCXFile)) {
        $docxPath = $conn->real_escape_string($targetDOCXFile);
/*
book_name
author
genre
description
book_type
price
rating
book_img
book_content
*/

        // Insert data into the database
        $sql = "INSERT INTO `books`(book_name, author, book_type, genre, price, rating, book_img, book_content) VALUES('$bookTitle', '$bookAuthor', '$bookType', '$bookGenre', '$bookPrice', '$bookRating', '$imagePath', '$docxPath')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Book uploaded successfully. It will be processed by the admin soon.");</script>';
        } else {
            if ($conn->errno == 1062) {
                echo '<script>alert("Book is already uploaded.");</script>';
            } else {
                echo '<script>alert("Error: ' . $sql . '\n' . $conn->error . '");</script>';
            }
        }
    } else {
        echo "Sorry, there was an error uploading your DOCX file.";
    }
}

        } else {
            echo "Sorry, there was an error uploading your image.";
        }
    }
}
}


function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}




if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT book_img FROM `books` WHERE book_id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('./ebooksFolder/'.$fetch_delete_image['book_img']);
   mysqli_query($conn, "DELETE FROM `books` WHERE book_id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `books` SET book_name = '$update_name', price = '$update_price' WHERE book_id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = './ebooksFolder/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `books` SET book_img = '$update_image' WHERE book_id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('./ebooksFolder/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}
if(isset($_POST['close'])){
   header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>products</title>

    <link rel="stylesheet" href="./admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../book_upload_style.css">



</head>

<body>

    <!-- product CRUD section starts  -->

    <section class="add-products">

        <h1 class="title">E books</h1>


        <div class="container">
        <h2>Upload Book</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="bookTitle">Book Title:</label>
            <input type="text" name="bookTitle" required>

            <label for="bookAuthor">Book Author:</label>
            <input type="text" name="bookAuthor" required>

            <label for="bookGenre">Book Genre:</label>
            <input type="text" name="bookGenre" required>

            <label for="bookType">Book type(paid/free):</label>
            <input type="text" name="bookType" required>

            <label for="bookDescription">Description:</label>
            <textarea name="bookDescription" rows="4" required></textarea>

            <label for="bookPrice">price:</label>
            <textarea name="bookPrice" type="num" required></textarea>

            <label for="bookDOCX">Upload DOCX:</label>
            <input type="file" name="bookDOCX" accept=".docx" required>

            <label for="bookImage">Book Image:</label>
            <input type="file" name="bookImage" accept=".jpg, .jpeg, .png, .gif" required>

            <input type="submit" value="Upload">
        </form>
    </div>


    </section>

    <!-- product CRUD section ends -->

    <!-- show products  -->

    <section class="show-products">

        <div class="box-container">

            <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `books`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
            <div class="box">
                <img src="../ebooksFolder/<?php echo $fetch_products['book_img']; ?>" alt="">
                <div class="name"><strong>Book title :</strong><?php echo $fetch_products['book_name']; ?></div>
                <div class="name"><strong>Author :</strong><?php echo $fetch_products['author']; ?></div>
                <div class="name"><strong>Book_type :</strong><?php echo $fetch_products['book_type']; ?></div>
                <div class="name"><strong>Genre :</strong><?php echo $fetch_products['genre']; ?></div>
                <div class="price"><strong>price :</strong>$<?php echo $fetch_products['price']; ?>/-</div>
                <div class="name"><strong>Rating :</strong><?php echo $fetch_products['rating']; ?></div>
                <div class="ml-auto">
                    <a href="admin_products.php?update=<?php echo $fetch_products['book_id']; ?>"
                        class="option-btn">update</a>
                    <a href="admin_products.php?delete=<?php echo $fetch_products['book_id']; ?>" class="delete-btn"
                        onclick="return confirm('delete this product?');">delete</a>
                </div>
            </div>
            <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
        </div>

    </section>

    <section class="edit-product-form">

        <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `books` WHERE book_id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['book_id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['book_img']; ?>">
            <img src="../ebooksFolder/<?php echo $fetch_update['book_img']; ?>" alt="">
            <input type="text" name="update_name" value="<?php echo $fetch_update['book_name']; ?>" class="box" required
                placeholder="enter product name">
            <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box"
                required placeholder="enter product price">
            <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
            <input type="submit" value="update" name="update_product" class="btn">
            &nbsp;<input type="reset" value="cancel" id="close-update" class="btn">
            &nbsp;<button class="btn" name="close" id="close">close</button>
        </form>
        <?php

         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

    </section>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <!-- custom admin js file link  -->
    <script src=".js/admin_script.js"></script>

</body>

</html>