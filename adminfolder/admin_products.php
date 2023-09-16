<?php

include '../additionals/_database.php';
include './admin_header.php';
if(!isset($_SESSION["user_id"])){
   header('location:index.html');
};

if(isset($_POST['add_product'])){

   $bookName = mysqli_real_escape_string($conn, $_POST['bookName']);
   $authorName = mysqli_real_escape_string($conn, $_POST['authorName']);
   $genre = mysqli_real_escape_string($conn, $_POST['genre']);
   $book_type = mysqli_real_escape_string($conn, $_POST['book_type']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = './ebooksFolder/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT book_name FROM `books` WHERE book_name = '$bookName'") or die('query failed 1');

   if(mysqli_num_rows($select_product_name) > 0){
    // message
    echo '
    <div class="alert alert-warning alert-dismissible fade show mt-5 pt-3" role="alert">
        <strong>book already exists</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `books`(book_name, author, book_type, genre, price, book_img) VALUES('$bookName', '$authorName', '$book_type', '$genre', '$price', '$image')") or die('query failed 2');

      if($add_product_query){
         if($image_size > 2000000){
            echo '
            <div class="alert alert-warning alert-dismissible fade show mt-5 pt-3" role="alert">
                <strong>image size is too large</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            echo '
            <div class="alert alert-warning alert-dismissible fade show mt-5 pt-3" role="alert">
                <strong>book uploaded successfully</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
         }
      }else{
        echo '
        <div class="alert alert-warning alert-dismissible fade show mt-5 pt-3" role="alert">
            <strong>products cannot be added</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
      }
   }
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



</head>

<body>

    <!-- product CRUD section starts  -->

    <section class="add-products">

        <h1 class="title">E books</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>add product</h3>
            <input type="text" name="bookName" class="box" placeholder="enter book name" required>
            <input type="text" name="authorName" class="box" placeholder="enter author name" required>
            <input type="text" name="book_type" class="box" placeholder="enter book_type" required>
            <input type="text" name="genre" class="box" placeholder="enter genre" required>
            <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="add product" name="add_product" class="btn">
        </form>

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