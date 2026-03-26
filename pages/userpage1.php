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
    header("Location: ./login.php");
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
  <title>BERMS - Emergency Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <div class="flex items-center gap-4">
          <div class="h-16 w-16 bg-white rounded-lg flex items-center justify-center overflow-hidden">
            <img src="../images/logo.jpg" alt="BERMS Logo" class="h-full w-full object-cover">
          </div>
          <h1 class="text-white text-xl md:text-2xl font-bold hidden md:block">BERMS</h1>
        </div>
        <p class="text-white text-lg md:text-xl font-semibold hidden sm:block">
          Welcome, <?php echo htmlspecialchars($user['firstName']); ?>
        </p>
        <form method="post" action="" class="inline-block">
          <button type="submit" name="logout" class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-2 px-6 rounded-lg transition duration-300">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          Report an Emergency
        </h2>
        <p class="text-lg text-gray-600">
          Select the type of emergency you want to report
        </p>
      </div>

      <!-- Emergency Buttons Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <!-- Fire Button -->
        <button onclick="report('Fire')" class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-8 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
          <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-fire"></i>
          </div>
          <div class="text-2xl font-bold">Fire</div>
          <p class="text-red-100 text-sm mt-2">Report a fire emergency</p>
        </button>

        <!-- Ambulance Button -->
        <button onclick="report('Ambulance')" class="group bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-8 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
          <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-ambulance"></i>
          </div>
          <div class="text-2xl font-bold">Ambulance</div>
          <p class="text-green-100 text-sm mt-2">Medical emergency</p>
        </button>

        <!-- Police Button -->
        <button onclick="report('Police')" class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-8 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
          <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-shield-alt"></i>
          </div>
          <div class="text-2xl font-bold">Barangay Tanod</div>
          <p class="text-blue-100 text-sm mt-2">Barangay security assistance</p>
        </button>
      </div>
    </div>
  </div>

  <script>
    function report(type){
      localStorage.setItem("emergencyType", type);
      window.location = "./reportform.php";
    }
  </script>
</body>
</html>