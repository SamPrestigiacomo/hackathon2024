<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>

<h1>Welcome, <?php echo $user_data['user_name']; ?></h1>
<?php if (!empty($user_data['profile_picture'])): ?>
    <img src="<?php echo $user_data['profile_picture']; ?>" alt="Profile Picture">
<?php else: ?>
    <p>No profile picture uploaded.</p>
<?php endif; ?>

</body>
</html>
