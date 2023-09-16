<?php

require './startpage.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Upload Book</title>
    <!-- <style>
    body {

        background: rgb(0, 36, 4);
        background: linear-gradient(90deg, rgba(0, 36, 4, 1) 0%, rgba(9, 120, 121, 0.8071603641456583) 22%, rgba(80, 130, 215, 1) 75%, rgba(0, 212, 255, 1) 100%);
    }


    .container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    textarea {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[type="file"] {
        margin-bottom: 10px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .error {
        color: #d9534f;
        margin-top: 5px;
    }

    .success {
        color: #5cb85c;
        margin-top: 5px;
    }

    .disclaimer {
        margin-top: 50px;
        background-color: #f0f0f0;
        padding: 10px;
        text-align: center;
        font-style: italic;
        color: #555;
    }
    </style> -->
    <link href="./book_upload_style.css" rel="stylesheet">
</head>

<body>
    <div class="disclaimer box">
        Please note that the book title and filenames for the book image and PDF should be the same.
    </div>
    <?php
require './additionals/_database.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookTitle = sanitizeInput($_POST["bookTitle"]);
    $bookAuthor = sanitizeInput($_POST["bookAuthor"]);
    $bookGenre = sanitizeInput($_POST["bookGenre"]);
    $bookDescription = sanitizeInput($_POST["bookDescription"]);
    $userEmail = $_SESSION["email"];

    // Handle image upload
    $targetImageDir = "donates/";
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

            // Handle book content (PDF) upload
// Handle DOCX file upload
$targetDOCXDir = "donates/";
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

        // Insert data into the database
        $sql = "INSERT INTO donate (user_email, book_title, book_author, book_genre, book_description, book_image, book_docx, status)
                VALUES ('$userEmail', '$bookTitle', '$bookAuthor', '$bookGenre', '$bookDescription', '$imagePath', '$docxPath', 'pending')";

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

$conn->close();

function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

?>

    <div class="container">
        <h2>Upload Book</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="bookTitle">Book Title:</label>
            <input type="text" name="bookTitle" required>

            <label for="bookAuthor">Book Author:</label>
            <input type="text" name="bookAuthor" required>

            <label for="bookGenre">Book Genre:</label>
            <input type="text" name="bookGenre" required>

            <label for="bookDescription">Description:</label>
            <textarea name="bookDescription" rows="4" required></textarea>

            <label for="bookDOCX">Upload DOCX:</label>
            <input type="file" name="bookDOCX" accept=".docx" required>

            <label for="bookImage">Book Image:</label>
            <input type="file" name="bookImage" accept=".jpg, .jpeg, .png, .gif" required>

            <input type="submit" value="Upload">
        </form>
    </div>






<!-- 
    <script src="https://kit.fontawesome.com/a59b9b09ab.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>

</html>