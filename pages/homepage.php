<?php
session_start();
include("../config/connect.php");

// Handle logout first
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ./logout.php");
    exit();
}

// If user is not logged in, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
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
    <title>BERMS - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
      <div class="text-center max-w-2xl">
        <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
          Welcome <?php echo htmlspecialchars($user['firstName']); ?>! 👋
        </h1>
        <p class="text-lg md:text-xl text-gray-600 mb-8">
          You're logged in as <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?>
        </p>
        <!-- Logout Button -->
        <form method="post" action="" class="inline-block">
          <button type="submit" name="logout" class="btn-primary px-8 py-3">Logout</button>
        </form>
      </div>
    </div>
</body>
</html>