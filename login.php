<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");
include("functions.php");

// Login functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $query = "SELECT * FROM users WHERE user_name = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            // Compare plain text passwords
            if ($user_data['password'] === $password) {
                $_SESSION['id'] = $user_data['id'];
                header("Location: index.php");
                exit;
            } else {
                echo "Wrong username or password!";
            }
        } else {
            echo "User does not exist!";
        }
    } else {
        echo "Invalid username or password.";
    }
}

?>


<!DOCTYPE html>
<head>
    <title>Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

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
        }
        #text {
            font-size: 1.2em;
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
            text-decoration: none;
            color: black;
            font-weight: 700;
            letter-spacing: 0.8px;
            border: 3px solid black;
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
            margin-top: 1em;
            background-color: black;
            color: white;
            border: none;
            text-decoration: none;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 1.2em;
        }
        .top_text {
            margin-top: 2em;
        }
        .buttons {
            padding-bottom: 2em;
        }


    </style>
    
</head>

<body>
    
    

    <div id="box">
        <form id="signup_form" method="post" action="" enctype="multipart/form-data">
            <input id="text" class="top_text" placeholder="username" type="text" name="user_name"><br><br>
            <input id="text" placeholder="password" type="password" name="password"><br><br>
            <div class="buttons">
                <input id="login" type="submit" value="Login" name="login">
                <a id="register" href="signup.php">Click to Register</a>
            </div>
</form>

    </div>

    
</body>
</html>
