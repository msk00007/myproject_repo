<?php
require "./admin_header.php";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="./admin_style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
   
    </style>
</head>

<body class="gradient-background">
    <div class="display-3 text-center text-light text-decoration-underline mt-2 p-2">CUSTOMER DETAILS</div>
    <div class="table-responsive">
    <?php
    require "../additionals/_database.php";
    $sql="SELECT * FROM userdetails;
    ";
    if($result=$conn->query($sql)){
        echo' <table class="table table-hover tableContent table-primary table-hover table-bordered border-light text-center ">
        <thead>
        <tr >
          <th scope="col" >sl no</th>
          <th scope="col">user name</th>
          <th scope="col">user email</th>
          <th scope="col">gender</th>
          <th scope="col">age group</th>
        </tr>
      </thead>';
      $sl_no=1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo'
            <tbody class="table-group-divider">
              <tr>
                <th scope="row">' .$sl_no. '</th>
                <td>' .$row["firstname"]. '</td>
                <td>' .$row["email"]. '</td>
                <td>' .$row["gender"]. '</td>
                <td>' .$row["agegroup"]. '</td>
              </tr>';
              $sl_no+=1;
            }
              echo'
            </tbody>
</table>';
    }
    ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>