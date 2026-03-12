<?php
session_start();
include("connect.php");

// If user is not logged in, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

// Fetch user info
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>
<body>
    <div style="text-align:center; padding:15%;">
      <p style="font-size:50px; font-weight:bold;">
       Hello <?php echo $user['firstName'] . ' ' . $user['lastName']; ?> :)
      </p>
      <!-- Logout Button -->
      <form method="post" action="">
          <button type="submit" name="logout" style="padding:10px 20px; font-size:20px;">Logout</button>
      </form>
    </div>

<?php
// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
</body>
</html>