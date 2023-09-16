<?php
require './startpage.php';
// require './additionals/startpage.php';
include './additionals/_database.php';

// Function to sanitize input
function sanitize($conn, $value)
{
    return mysqli_real_escape_string($conn, $value);
}

// Retrieve search value and type
$getBookDetails = isset($_POST['book_name']) ? sanitize($conn, $_POST['book_name']) : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="./style.css">
</head>

<body class="gradient-background container-fluid">
    <div class="display-3 text-dark text-center mt-3 pt-5">
        <p>welcome to eBooks</p>
    </div>
    <div class="mt-2 sticky-top">
        <nav class="navbar border bg-border-light">
            <div class="container-fluid d-flex justify-content-end ">
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" id="getSearch">
                    <select class="form-select" aria-label="Default select example" id="search_type">
                        <option selected>Select search type</option>
                        <option value="genre">genre</option>
                        <option value="rating">Rating</option>
                        <option value="name">Name</option>
                    </select>
                </form>
            </div>
        </nav>
    </div>
    <div class="container-fluid" id="showBoolDetails">
        <div class="h1 text-center pt-3 border-bottom border-primary">Free collection</div>
        <div class="cover">
            <button class="left" onclick="leftScroll('scroll-images-1')">
                <i class="fas fa-angle-double-left"></i>
            </button>
            <div class="scroll-images" id="scroll-images-1">
                <?php
                $sql = "SELECT * FROM books WHERE book_type='free'";
                $stmt = mysqli_prepare($conn, $sql);
                if (!empty($getBookDetails)) {
                    mysqli_stmt_bind_param($stmt, 's', $getBookDetails);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="card child shadow p-3 mb-5 bg-body-tertiary rounded" style="width: 18rem;">
                    <img src="./ebooksFolder/'.$row["book_img"].'" class="img-thumbnail" alt="...">

                        <div class="card-body">
                            <h5 class="card-title">' . $row["book_name"] . '</h5>
                            <p class="card-text">' . $row["author"] . '</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Genre</strong> : ' . $row["genre"] . '</li>
                            <li class="list-group-item"><strong>Price</strong> : ₹' . $row["price"] . '</li>
                            <li class="list-group-item"><strong>Rating</strong> :' . $row["rating"] . '</li>
                        </ul>
                        <div class="card-body">
                        <button class="btn btn-primary add-to-cart" data-book-id="'.$row["book_id"].'">Add to Cart</button>


                        </div>
                    </div>';
                }
                ?>
            </div>
            <button class="right" onclick="rightScroll('scroll-images-1')">
                <i class="fas fa-angle-double-right"></i>
            </button>
        </div>

        <!-- second scroll -->

        <div class="h1 text-center p-2 border-bottom border-primary">Exclusive collection</div>

        <div class="cover">
            <button class="left" onclick="leftScroll('scroll-images-2')">
                <i class="fas fa-angle-double-left"></i>
            </button>
            <div class="scroll-images" id="scroll-images-2">
                <?php
                $sql = "SELECT * FROM books WHERE book_type='paid'";
 
                $stmt = mysqli_prepare($conn, $sql);
                if (!empty($getBookDetails)) {
                    mysqli_stmt_bind_param($stmt, 's', $getBookDetails);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="card child shadow p-3 mb-5 bg-body-tertiary rounded" style="width: 18rem;">
                    <img src="./ebooksFolder/'.$row["book_img"].'" class="img-thumbnail" alt="...">

                        <div class="card-body">
                            <h5 class="card-title">' . $row["book_name"] . '</h5>
                            <p class="card-text">' . $row["author"] . '</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Genre</strong> : ' . $row["genre"] . '</li>
                            <li class="list-group-item"><strong>Price</strong> : ₹' . $row["price"] . '</li>
                            <li class="list-group-item"><strong>Rating</strong> :' . $row["rating"] . '</li>
                        </ul>
                        <div class="card-body">
                        <button class="btn btn-primary add-to-cart" data-book-id="'.$row['book_id'].'">Add to Cart</button>

                        </div>
                    </div>';
                }
                ?>
            </div>
            <button class="right" onclick="rightScroll('scroll-images-2')">
                <i class="fas fa-angle-double-right"></i>
            </button>
        </div>
   


    <!-- third scroll -->

    <div class="h1 text-center p-2 border-bottom border-primary">Donated books</div>

    <div class="cover">
        <button class="left" onclick="leftScroll('scroll-images-3')">
            <i class="fas fa-angle-double-left"></i>
        </button>
        <div class="scroll-images" id="scroll-images-3">
            <?php
                $sql = "SELECT * FROM donate WHERE status='success'";

                $stmt = mysqli_prepare($conn, $sql);
                if (!empty($getBookDetails)) {
                    mysqli_stmt_bind_param($stmt, 's', $getBookDetails);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
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
                ?>
        </div>
        <button class="right" onclick="rightScroll('scroll-images-3')">
            <i class="fas fa-angle-double-right"></i>
        </button>
    </div>
    </div>

    </div>
    <!-- footer -->

    <?php include './footer.html'; ?>

    <!-- script -->

    <script src="https://kit.fontawesome.com/a59b9b09ab.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <script src="./js/script.js"></script>


    <script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(event) {
            event.preventDefault();

            // Get the book ID from the data attribute
            var bookId = $(this).data('book-id');
            // Send an AJAX request to add the book to the cart
            $.ajax({
                url: 'add_to_cart.php', 
                type: 'POST',
                data: {
                    book_id: bookId
                },
                // Send the book ID in the request
                success: function(response) {
                    // Handle the response from the server
                    if (response.success) {
                        alert('Book added to the cart!');
                    } else {
                        alert('Failed to add the book to the cart.');
                    }
                },
                error: function() {
                    alert('An error occurred while processing the request.');
                }
            });
        });
    });
    </script>
</body>

</html>