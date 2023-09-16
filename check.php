<?php
include './additionals/_database.php';
include './startpage.php';

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <div class="h1 text-center pt-3">Free collection</div>
        <div class="cover">
            <button class="left" onclick="leftScroll('scroll-images-1')">
                <i class="fas fa-angle-double-left"></i>
            </button>
            <div class="scroll-images" id="scroll-images-1">
                <?php
                $sql = "SELECT * FROM books WHERE book_type='free'";
                if (!empty($getBookDetails)) {
                    $searchType = $_POST['search_type'];
                    if ($searchType === 'genre') {
                        $sql = "SELECT * FROM books WHERE book_type='free' AND genre=?";
                    } elseif ($searchType === 'rating') {
                        $sql = "SELECT * FROM books WHERE book_type='free' AND rating=?";
                    } elseif ($searchType === 'name') {
                        $sql = "SELECT * FROM books WHERE book_type='free' AND book_name LIKE ?";
                        $getBookDetails = "%$getBookDetails%";
                    }
                }
                $stmt = mysqli_prepare($conn, $sql);
                if (!empty($getBookDetails)) {
                    mysqli_stmt_bind_param($stmt, 's', $getBookDetails);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="card child" style="width: 18rem;">
                        <img src="download.jpg" class="card-img-top" alt="...">
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
                            <a href="#" class="card-link">Add to cart</a>
                            <a href="#" class="card-link">Buy now</a>
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

        <div class="h1 text-center p-2">Exclusive collection</div>

        <div class="cover">
            <button class="left" onclick="leftScroll('scroll-images-2')">
                <i class="fas fa-angle-double-left"></i>
            </button>
            <div class="scroll-images" id="scroll-images-2">
                <?php
                $sql = "SELECT * FROM books WHERE book_type='paid'";
                if (!empty($getBookDetails)) {
                    $searchType = $_POST['search_type'];
                    if ($searchType === 'genre') {
                        $sql = "SELECT * FROM books WHERE book_type='paid' AND genre=?";
                    } elseif ($searchType === 'rating') {
                        $sql = "SELECT * FROM books WHERE book_type='paid' AND rating=?";
                    } elseif ($searchType === 'name') {
                        $sql = "SELECT * FROM books WHERE book_type='paid' AND book_name LIKE ?";
                        $getBookDetails = "%$getBookDetails%";
                    }
                }
                $stmt = mysqli_prepare($conn, $sql);
                if (!empty($getBookDetails)) {
                    mysqli_stmt_bind_param($stmt, 's', $getBookDetails);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="card child" style="width: 18rem;">
                        <img src="download.jpg" class="card-img-top" alt="...">
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
                            <a href="#" class="card-link">Add to cart</a>
                            <a href="#" class="card-link">Buy now</a>
                        </div>
                    </div>';
                }
                ?>
            </div>
            <button class="right" onclick="rightScroll('scroll-images-2')">
                <i class="fas fa-angle-double-right"></i>
            </button>
        </div>
    </div>

    <!-- footer -->

    <?php include './footer.html'; ?>

    <!-- script -->
    <script>
        function leftScroll(elementId) {
            const container = document.querySelector(`#${elementId}`);
            container.scrollBy(-200, 0);
        }

        function rightScroll(elementId) {
            const container = document.querySelector(`#${elementId}`);
            container.scrollBy(200, 0);
        }
    </script>
    <script src="https://kit.fontawesome.com/a59b9b09ab.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>

</html>
<script>
    $(document).ready(function() {
        var previousHTML = $('#showBoolDetails').html();
        $('#getSearch').on("keyup", function() {
            var getBookdetails = $(this).val();
            var dropdownValue = $('#search_type').val();
            if (getBookdetails === '') {
                $(this).val('');
                $('#showBoolDetails').empty().html(previousHTML);
                $("#showBoolDetails").removeClass("flexContent");
            } else {
                var valueToBeFetched = getBookdetails + ' ' + dropdownValue;
                $.ajax({
                    method: 'POST',
                    url: 'searchBook.php',
                    data: {
                        book_name: valueToBeFetched
                    },
                    success: function(response) {
                        $("#showBoolDetails").addClass("flexContent");
                        $("#showBoolDetails").html(response);
                    }
                });
            }
        });
    });
</script>
