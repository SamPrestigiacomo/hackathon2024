<?php
session_start();

include("connection.php");
include("functions.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);


$user_data = check_login($con);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $post_title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_STRING);
    $post_text = filter_input(INPUT_POST, 'post_text', FILTER_SANITIZE_STRING);
    
    // Retrieve the id of the logged-in user
    $user_id = $user_data['id'];

    // Insert the post into the database
    $stmt_insert = $con->prepare("INSERT INTO posts (user_id, post_title, post_text, created_at) VALUES (?, ?, ?, NOW())");
    $stmt_insert->bind_param("iss", $user_id, $post_title, $post_text);


    if ($stmt_insert->execute()) {
        // Post inserted successfully
        header("Location: index.php");
        exit;
    } else {
        // Error inserting post
        echo "Error: Unable to insert post." . $con->error;
    }
    $stmt_insert->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Post</title>
</head>
<body>
    <form method="post" action="add_post.php">
        <label for="post_title">Post Title:</label><br>
        <input type="text" id="post_title" name="post_title"><br>
        <label for="post_text">Post Text:</label><br>
        <textarea id="post_text" name="post_text"></textarea><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
