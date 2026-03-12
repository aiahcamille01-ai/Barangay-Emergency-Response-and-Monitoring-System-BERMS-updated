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
<html>
<head>

<title>Emergency Dashboard</title>

<style>

body{
font-family:Arial;
text-align:center;
background:#fafafa;
}

button{
padding:15px 30px;
margin:20px;
font-size:18px;
cursor:pointer;
}

.fire{
background:red;
color:white;
}

.ambu{
background:green;
color:white;
}

.police{
background:blue;
color:white;
}

</style>

</head>

<body>

<nav style="background: blue; color: white; padding: 20px; padding: 5px; display: flex; justify-content: space-between; align-items: center;">
  <img src="images/logo.jpg" alt="Logo" style="height: 70px;">
  <p style="font-size: 2rem; font-weight:bold; margin: 0;">
       Welcome <?php echo $user['firstName'] . ' ' . $user['lastName']; ?>
      </p>
      <form method="post" action="">
          <button type="submit" name="logout" style=" padding: 5px 13px 5px 13px; font-size: 1rem; border-radius: 5px; border: none;">Logout</button>
      </form>
      <?php
// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
</nav>

<h1>Select Emergency Type</h1>

<button class="fire" onclick="report('Fire')">Fire</button>

<button class="ambu" onclick="report('Ambulance')">Ambulance</button>

<button class="police" onclick="report('Police')">Police</button>

<script>

function report(type){
localStorage.setItem("emergencyType", type);
window.location = "reportform.php";
}

</script>

</body>
</html>