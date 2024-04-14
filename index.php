<?php
session_start();



// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include("connection.php");
include("functions.php");

$user_data = check_login($con);
?>

<!DOCTYPE html>
<head>
    <title>Timeline</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0 2em;
        }
        #chain_posts {
            max-height: 70vh;
            margin-top: -.5em;
            border-left: 6px solid rgba(0, 229, 59, 0.8);;
            border-bottom: 4px solid green;
            padding-top: 6em;
            margin-bottom: 2em;
            width: 70%;
        }
        .top {
            width: 60vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-left: auto;
            padding-top: 1em;
        }
        #add_post {
            background-color: black;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            transition: all .35s ease;
        }
        #add_post:hover {
            color: rgba(0, 229, 59, 0.8);
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }
        .connected_links {
            display: flex;
            align-items: center;
        }
        .connected_links #add_post {
            margin-right: 2em;
        }
        #logout {
            text-decoration: none;
            color: black;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all .35s ease-in-out;
        }
        #logout:hover {
            color: rgba(0, 229, 59, 0.8);
        }
        #pfp {
            width: 115px;
            height: 115px;
            border: 6px solid rgba(0, 229, 59, 0.8);
            border-radius: 100%;
            margin-left: 0%;
            float: left;
            top: 50px;
            left: 0px;
            object-fit: cover;
        }
        h2 {
            border-top: 2px solid rgba(0, 229, 59, 0.8);
            padding-top: .5em;
            margin-left: 0;
        }
        h2,p,small{
            padding-left: 2.5vw;
        }
        .container {
            display:flex;
        }
        aside {
            overflow: hidden;
            top: 0;
            background-color: #f2f2f2;
            padding: 10px;
            max-height: auto;
            width: auto;
            margin-left: auto;
        }
        aside img {
            width: 75px;
            height: 75px;
            object-fit: cover;
        }
        .user-list {
            display: flex;
            flex-direction: column;
        }

        .user {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user span {
            font-weight: bold;
        }
        .together {
            display: flex;
            /* align-items: center; */
            /* justify-content: center; */
            gap: 50px;
            width: auto;
            height: auto;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="top">
    <h1>
                Hello, <?php echo $user_data['user_name']; ?>
            </h1>
        <div class="together">
            <div class="connected_links">
                <a id="add_post" href="add_post.php">Add A Post</a>
                <form action="search.php" enctype="multipart/form-data" method="GET">
                    <input class="search_text" type="text" name="username" placeholder="Search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
                <a id="logout" href="logout.php">Logout</a>
                <?php
                // Display the profile picture upload form only for the logged-in user
                ?>
            </div>
        
        </div>
    </div>

    <br>

    <a href="profile_picture.php">
        <img id="pfp" src="<?php echo empty($user_data['profile_picture']) ? 'default.png' : $user_data['profile_picture']; ?>">
    </a>

    <div class="container">
        <div id="chain_posts">
            <!-- Add posts -->
            <?php
            $stmt = $con->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY id ASC");
            $stmt->bind_param("i", $user_data['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            $posts = array(); // Initialize an empty array to store the posts

            while ($row = $result->fetch_assoc()) {
                // Store each post in the array
                $posts[] = $row;
            }

            // Reverse the array to display the newest posts first
            $posts = array_reverse($posts);

            // Now you can iterate over $posts to display the posts in the desired order
            foreach ($posts as $post) {
                echo "<h2>" . $post['post_title'] . "</h2>";
                echo "<p>" . $post['post_text'] . "</p>";
                echo "<small>Posted on: " . $post['created_at'] . "</small>";
            }
            ?>
        </div>

        <!-- USERS TO FOLLOW -->
        <aside>
            <h3>Users to Follow</h3>
            <ul class="user-list">
                <?php
                // Fetch users from the database (excluding the logged-in user)
                $stmt = $con->prepare("SELECT * FROM users WHERE id != ?");
                $stmt->bind_param("i", $user_data['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                // Display each user with their profile picture
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='user'>";
                    echo "<img src='{$row['profile_picture']}' alt='Profile Picture'>";
                    echo "<span>{$row['user_name']}</span>";
                    // You can provide a follow button or link for each user
                    // echo "<a href='follow.php?id={$row['id']}'>Follow</a>";
                    echo "</li>";
                }
                ?>
            </ul>
        </aside>
    </div>
</body>
</html>
