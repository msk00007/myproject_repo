<?php require './admin_header.php';
    require '../additionals/_database.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin order page</title>
       <!-- custom admin css file link  -->
   <link rel="stylesheet" href="./admin_style.css">
   <link rel="stylesheet" href="../style.css">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   </head>
  <body class="gradient-background-2">
  <div class="table-responsive">
<?php
    $sql="SELECT * FROM customer_orders;
    ";
    if($result=$conn->query($sql)){
        echo' <table class="table table-hover tableContent">
        <thead>
        <tr>
          <th scope="col">Order id</th>
          <th scope="col">Book Title</th>
          <th scope="col">User id</th>
          <th scope="col">Purchase time</th>
          <th scope="col">Purchase Status</th>
        </tr>
      </thead>';
        while ($row = mysqli_fetch_assoc($result)) {
            if($row["purchase_status"]=="success"){
            echo'
            <tbody class="table-group-divider">
              <tr>
                <th scope="row">' .$row["order_id"]. '</th>
                <td>' .$row["book_title"]. '</td>
                <td>' .$row["user_id"]. '</td>
                <td>' .$row["purchase_time"]. '</td>
                <td>' .$row["purchase_status"]. '</td>
              </tr>';}
            }
              echo'
            </tbody>
</table>';
    }
    ?>
    </div>
<div class="display-1 text-center border-bottom">Pending orders</div>
<div class="table-responsive">
<?php
 if($result=$conn->query($sql)){
    echo' <table class="table table-hover tableContent">
    <thead>
    <tr>
      <th scope="col">Order id</th>
      <th scope="col">Book Title</th>
      <th scope="col">User id</th>
      <th scope="col">Purchase time</th>
      <th scope="col">Purchase Status</th>
    </tr>
  </thead>';
    while ($row = mysqli_fetch_assoc($result)) {
        if($row["purchase_status"]=="pending"){
        echo'
        <tbody class="table-group-divider">
          <tr>
            <th scope="row">' .$row["order_id"]. '</th>
            <td>' .$row["book_title"]. '</td>
            <td>' .$row["user_id"]. '</td>
            <td>' .$row["purchase_time"]. '</td>
            <td><button id="changeStatus" class="changeStatus btn pending">' .$row["purchase_status"]. '</button></td>
          </tr>';}
        }
          echo'
        </tbody>
</table>';
}
?>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      $('.changeStatus').on('click', function() {
                
                if ($(this).text().trim() === "pending") {
                    console.log($(this).text().trim());
                    $(this).text("Success").removeClass("pending").addClass("success");
                }
            });
  $('.changeStatus').on('click', function() {
    var orderId = $(this).closest('tr').find('th').text();
    $.ajax({
      url: 'update_order_status.php', // PHP script to handle this request
      method: 'POST',
      data: {
        orderId: orderId
      },
      success: function(response) {
        if (response === 'success') {
    
          var row = $(this).closest('tr');
          row.remove();
          $('#successOrdersTable tbody').append(row);
        }
      }
    })
    ;


  });
});
</script>

  </body>
</html>