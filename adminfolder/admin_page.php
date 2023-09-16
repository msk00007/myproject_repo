 <?php
 require '../additionals/_database.php';

 
?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>admin panel</title>

     <!-- font awesome cdn link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

     <!-- custom admin css file link  -->
     <link rel="stylesheet" href="./admin_style.css">
     <link rel="stylesheet" href="../style.css">

 </head>

 <body class="gradient-background">

     <?php include 'admin_header.php'; ?>

     <!-- admin dashboard section starts  -->

     <section class="dashboard">

         <h1 class=" text-center my-3 display-1">DASHBOARD</h1>

         <div class="box-container">

             <div class="box">
                 <!-- total pendings -->
                 <?php
            $total_pendings = 0;
            $sql = "SELECT ALL b.price
        FROM books b
        INNER JOIN customer_orders c ON c.book_title=b.book_name  
        WHERE c.purchase_status = 'pending'";
        $select_pending = mysqli_query($conn, $sql);
            // $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            if(mysqli_num_rows($select_pending) > 0){
               while($fetch_pendings = mysqli_fetch_assoc($select_pending)){
                  $total_price = $fetch_pendings['price'];
                  $total_pendings += $total_price;
               };
            };
         ?>
                 <h3>₹<?php echo $total_pendings; ?>/-</h3>
                 <p>total pendings</p>
             </div>
             <!-- completed payments -->
             <div class="box">
                 <?php
            $total_completed = 0;
            $sql = "SELECT b.price
            FROM books b
            INNER JOIN customer_orders c ON b.book_name=c.book_title  
            WHERE c.purchase_status = 'success'";
            $select_success= mysqli_query($conn, $sql);
                // $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                if(mysqli_num_rows($select_success) > 0){
                   while($fetch_success = mysqli_fetch_assoc($select_success)){
                      $total_price = $fetch_success['price'];
                      $total_completed+= $total_price;
                   };
                };
         ?>
                 <h3>₹<?php echo $total_completed; ?>/-</h3>
                 <p>completed payments</p>
             </div>

             <div class="box">
                 <?php 
            $select_orders = mysqli_query($conn, "SELECT * FROM `customer_orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
                 <h3><?php echo $number_of_orders; ?></h3>
                 <p>order placed</p>
             </div>
             <!-- products added -->
             <div class="box">
                 <?php 
            $select_products = mysqli_query($conn, "SELECT * FROM `books`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
                 <h3><?php echo $number_of_products; ?></h3>
                 <p>products added</p>
             </div>
             <!-- normal users -->
             <div class="box">
                 <?php 
            $select_users = mysqli_query($conn, "SELECT * FROM `userdetails`") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
                 <h3><?php echo $number_of_users; ?></h3>
                 <p>normal users</p>
             </div>
             <!-- Admin -->
             <div class="box">
                 <?php 
            $select_admins = mysqli_query($conn, "SELECT * FROM `admin_details`") or die('query failed');
            $number_of_admins = mysqli_num_rows($select_admins);
            ?>
                 <h3><?php echo $number_of_admins; ?></h3>
                 <p>admin users</p>
             </div>
             <!-- total users -->
             <div class="box">
                 <?php 
            $select_account = mysqli_query($conn, "SELECT * FROM `userdetails`") or die('query failed');
            $select_admins = mysqli_query($conn, "SELECT * FROM `admin_details`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account) + mysqli_num_rows($select_admins);
         ?>
                 <h3><?php echo $number_of_account; ?></h3>
                 <p>total accounts</p>
             </div>
             <!-- queries -->
             <div class="box">
                 <?php 
            $select_messages = mysqli_query($conn, "SELECT * FROM `customer_messages`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
                 <h3><?php echo $number_of_messages; ?></h3>
                 <p>new messages</p>
             </div>

         </div>

     </section>

     <!-- admin dashboard section ends -->









     <!-- custom admin js file link  -->

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
     </script>

 </body>

 </html>