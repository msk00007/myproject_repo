<?php 
include '../myproject1/additionals/_database.php';
 
   $book_details = $_POST['book_name'];
   $values = explode(' ', $book_details);
   $searchField=$values[0];
   if($values[1]==="genre"){
    $sql = "SELECT * FROM books WHERE genre LIKE '$searchField%'";
   } 
   elseif($values[1]=="rating"){
    $sql = "SELECT * FROM books WHERE Rating LIKE '$searchField%'";
   } 
   else{
   $sql = "SELECT * FROM books WHERE book_name LIKE '$searchField%'"; 
  }


   $query = mysqli_query($conn,$sql);
   $data='';
   while($row = mysqli_fetch_assoc($query))
   {
       $data .=  '
       <div class="card child shadow p-3 mb-5 bg-body-tertiary rounded" style="width: 18rem;">
       <img src="./ebooksFolder/'.$row["book_img"].'" class="img-thumbnail" alt="...">

           <div class="card-body">
               <h5 class="card-title">' . $row["book_name"] . '</h5>
               <p class="card-text">' . $row["author"] . '</p>
           </div>
           <ul class="list-group list-group-flush">
               <li class="list-group-item"><strong>Genre</strong> : ' . $row["genre"] . '</li>
               <li class="list-group-item"><strong>Price</strong> : ' . $row["price"] . '</li>
               <li class="list-group-item"><strong>Rating</strong> :' . $row["rating"] . '</li>
           </ul>
           <div class="card-body">
           <button class="btn btn-primary add-to-cart" data-book-id="'.$row["book_id"].'">Add to Cart</button>


           </div>
       </div>';
   }

   if($values[1]==="genre"){
    $sql = "SELECT * FROM donate WHERE book_genre LIKE '$searchField%'";
   } 
   elseif($values[1]=="rating"){
    $sql = "SELECT * FROM donate WHERE book_rating LIKE '$searchField%'";
   } 
   else{
   $sql = "SELECT * FROM donate WHERE book_title LIKE '$searchField%'"; 
  }


   $query = mysqli_query($conn,$sql);

   while($row = mysqli_fetch_assoc($query))
   {
       $data .=  '
       <div class="card child shadow p-3 mb-5 bg-body-tertiary rounded" style="width: 18rem;">
       <img src="./'.$row["book_image"].'" class="img-thumbnail" alt="...">

           <div class="card-body">
               <h5 class="card-title">' . $row["book_title"] . '</h5>
               <p class="card-text">' . $row["book_author"] . '</p>
           </div>
           <ul class="list-group list-group-flush">
               <li class="list-group-item"><strong>Genre</strong> : ' . $row["book_genre"] . '</li>
               <li class="list-group-item"><strong>Donated by </strong> : ' . $row["user_email"] . '</li>
               <li class="list-group-item"><strong>Rating</strong> :' . $row["book_rating"] . '</li>
           </ul>
           <div class="card-body">
           <button class="btn btn-primary add-to-cart" data-book-id="'.$row['donate_id'].'">Add to List</button>

           </div>
       </div>';
   }


   if($data==''){
    echo '<div class="display-1 text-center mt-5">Oops no results found!!</div>';
   }
   else{
    echo $data;
   }
  
 ?>