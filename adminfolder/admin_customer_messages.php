<?php
require "./admin_header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popover with PHP Content</title>
   
    <link href="admin_style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="gradient-background">
<div class="container">
<div class="table-responsive">
<?php
require "../additionals/_database.php";
$sql="SELECT u.firstname, c.id,c.user_id, c.message FROM customer_messages c INNER JOIN userdetails u ON c.user_id = u.email";
    if($result=$conn->query($sql)){
        echo' <table class="table table-hover tableContent table-primary table-hover table-bordered border-light text-center ">
        <thead>
        <tr>
          <th scope="col">message id</th>
          <th scope="col">sender mail</th>
          <th scope="col">message</th>
        </tr>
      </thead>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo'
            <tbody class="table-group-divider text-center">
              <tr>
                <th scope="row">' .$row["id"]. '</th>
                <td>' .$row["user_id"]. '</td>
                <td>  <button
                type="button"
                class="btn btn-primary m-auto"
                data-bs-toggle="popover"
                data-bs-placement="right"
                title="'.$row["firstname"].'"
                data-bs-content="<h3><strong>' .$row["message"]. '</strong></h3>"
                data-bs-html="true" 
            >
                message
            </button></td>

              </tr>';
            }
              echo'
            </tbody>
</table>';
    }
    ?>
    </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>

    <!-- Initializing the popover -->
    <script>
        $(document).ready(function () {
            $('[data-bs-toggle="popover"]').popover();
        });
    </script>
</body>
</html>
