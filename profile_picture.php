<?php
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include("connection.php");
include("functions.php");

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit; // Stop executing the script
}

// Process profile picture upload if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $target_id == $id) {
    // Check if the file upload field is set and not empty
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        // File upload handling
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // File uploaded successfully, proceed with updating profile picture path in database
            $profile_picture = $target_file;

            // Update profile picture path in users table for the logged-in user
            $update_query = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $con->prepare($update_query);
            $stmt->bind_param("si", $profile_picture, $id);
            
            if ($stmt->execute()) {
                echo "Profile picture updated successfully";
                header("Location: index.php");
                exit; // Stop executing the script
            } else {
                echo "Error updating profile picture: " . $stmt->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Please select a file to upload.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Picture Upload</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <style>
        body {
            height: 100vh;
            font-family: "Poppins", sans-serif;
            display: flex;
            align-items: center;
        }
        form {
            text-align: center;
            width: 70%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            
        }
        #pfp_content {
            font-weight: 700;
            display: flex;
            align-items: center;
            min-height: 35vh;
            width: 75vw;
            margin: 2em auto;
            background-color: rgba(0, 229, 59, 0.8);
            max-height: 30vh;
        }

        #upload_file {
            width: auto;
            text-align: center;
            color: white;
            font-weight: 700;
            letter-spacing: 0.8px;
            background-color: black;
            border: none;
            padding: 20px 20px;
            margin-top: 1em;
            font-size: 0.9em;
        }


    </style>

<div id="pfp_content">
    <form method="post" action="profile_picture.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fileToUpload" class="form-label">Select Profile Picture:</label>
            <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
        </div>
        <input type="submit" value="Upload" name="submit">
    </form>

</div>


</body>
</html>
