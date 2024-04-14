<?php
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the username from the form
    $search_username = $_GET["username"];

    // Query the database to retrieve user data based on the username
    $stmt = $con->prepare("SELECT * FROM users WHERE user_name LIKE ?");
    $search_username = "%" . $search_username . "%"; // Add wildcards to search for partial matches
    $stmt->bind_param("s", $search_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the HTML with CSS for search results
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Search Results</title>";
    echo "<style>";
    echo "body {";
    echo "    font-family: Arial, sans-serif;";
    echo "    margin: 0;";
    echo "}";
    echo "#chain_posts {";
    echo "    padding: 20px;";
    echo "}";
    echo ".profile {";
    echo "    margin-bottom: 20px;";
    echo "}";
    echo "#pfp {";
    echo "    width: 100px;";
    echo "    height: 100px;";
    echo "    border: 6px solid green;";
    echo "    border-radius: 50%;";
    echo "    margin-right: 20px;";
    echo "    float: left;";
    echo "}";
    echo ".post {";
    echo "    border-bottom: 2px solid green;";
    echo "    padding-bottom: 20px;";
    echo "}";
    echo ".post h2 {";
    echo "    margin-top: 0;";
    echo "}";
    echo ".post p, .post small {";
    echo "    margin-bottom: 0;";
    echo "}";
    echo "</style>";
    echo "</head>";
    echo "<body>";

    echo "<div id='chain_posts'>";

    while ($row = $result->fetch_assoc()) {
        // Profile Picture
        echo "<div class='profile'>";
        if ($row['id'] == $_SESSION['id']){
            echo "<a href='profile_picture.php'>";
        }
        
        echo "<img id='pfp' src='" . ($row['profile_picture'] ? $row['profile_picture'] : 'default.png') . "'>";
        echo "</a>";

        // Username
        echo "<h3>" . $row['user_name'] . "</h3>";
        echo $row['id'];

        echo "</div>";
        // Query the database to retrieve posts by the user
        $stmt_posts = $con->prepare("SELECT * FROM posts WHERE id = ? ORDER BY created_at DESC");
        $stmt_posts->bind_param("i", $row['id']);
        $stmt_posts->execute();
        $result_posts = $stmt_posts->get_result();

        while ($post = $result_posts->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<h2>" . $post['post_title'] . "</h2>";
            echo "<p>" . $post['post_text'] . "</p>";
            echo "<small>Posted on: " . $post['created_at'] . "</small>";
            echo "</div>";
        }
    }

    echo "</div>"; // End of #chain_posts

    echo "</body>";
    echo "</html>";
}
?>



<!DOCTYPE html>
<html>
<head>

    <style>
        body {
            float: left;
            height: auto;
        }
        img {
            object-fit: cover;
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-direction: row;
        }
        
    </style>
</head>



</html>
