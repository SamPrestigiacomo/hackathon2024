<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");
include("functions.php");

// Registration functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    // Assuming profile picture handling code is here
    echo "$user_name";
    if (!empty($user_name) && !empty($password)) {
        // Insert user data into the database
        $id = 0;
        $profile_picture = "";
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            // File upload handling
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // File uploaded successfully, proceed with updating profile picture path in database
                $profile_picture = $target_file;
            
            }
        }
        $query = "INSERT INTO users (user_name, password, profile_picture, id) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('sssi',$user_name, $password, $profile_picture, $id);
        
        $stmt->execute();
        echo "Error: ", $stmt->error;

        if ($stmt->affected_rows > 0) {
            // Registration successful
            $_SESSION['registration_success'] = true; // Store a session variable to indicate successful registration
            header("Location: login.php");
            exit;
        } else {
            echo "Error registering user.";
        }
    }   
}
?>

<!DOCTYPE html>
<head>
    <title>Signup</title>

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
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        #box {
            height: auto;
            width: 50%;
            background-color: rgba(0, 229, 59, 0.8);
            margin: 0 auto;
            font-size: 1.2em;
        }
        #text {
            color: black;
            height: 25px;
            border-radius:5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }
        label {
            font-weight: 700;
        }
        
        #button {
            text-align: column;
            padding: 20px 30px;
            width: 100px;
            color: white;
            font-weight: 700;
            letter-spacing: 0.8px;
            background-color: black;
            border: none;
        }
        #button:hover {
            cursor: pointer;
        }
        form {
            width:80%;
            margin: 0 auto;
            /* display: flex; */
            text-align: center;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }
    
        #login_text {
            font-weight: 700;
            color: black;
            /* padding: 10px 20px; */
            font-size: 20px;
            margin-top: 2em;
        }
        #signup {
            color: black;
            text-decoration: none;
            padding: 15px 25px;
            /* border-radius: 8px; */
            border: 3px solid black;
        }
        #top_label {
            margin-top: 1em;
        }
        #signup_form {
            margin-top: 2em;
            margin-bottom: 2em;

        }
        #register {
            color: white;
            font-weight: 700;
            letter-spacing: 0.8px;
            background-color: black;
            border: none;
            padding: 10px 20px;
            margin-top: 1em;
            font-size: 0.9em;
        }
        .buttons {
            width: 40%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }
        #login {
            padding: 10px 20px;
            font-weight: 700;
            margin-top: 1em;
            border: 3px solid black;
            color: black;
            text-decoration: none;
        }

    </style>

    <div id="box">
        <form id="signup_form" method="post" enctype="multipart/form-data">
            <input id="text" placeholder="username" type="text" name="user_name"><br><br>
            <input id="text" placeholder="password" type="password" name="password"><br><br>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Add a Profile Picture</label>
                <input class="form-control" type="file" id="profile_picture" name="profile_picture">
            </div>
            <div class="buttons">
                <input id="register" type="submit" value="Register" name="register">
                <a id="login" href="login.php">Click to Login</a>
            </div>
        </form>
    </div>
</body>
</html>









