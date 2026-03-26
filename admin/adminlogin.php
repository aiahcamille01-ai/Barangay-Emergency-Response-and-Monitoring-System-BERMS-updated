<?php
session_start();
include("../config/connect.php");

$error = '';
$success = '';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['admin'] = true;
        $success = "true";
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BERMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .input-field {
            width: 100%;
            padding: 16px 16px 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        .floating-label label {
            transition: all 0.3s ease;
            pointer-events: none;
        }
        .input-field:focus ~ label,
        .input-field:not(:placeholder-shown) ~ label {
            transform: translateY(-1.5rem);
            font-size: 0.875rem;
            color: #3498db;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-blue-50 to-orange-50 min-h-screen flex items-center justify-center p-4">
    <!-- Back to Home Button -->
    <a href="../index.php" class="absolute top-4 left-4 text-orange-600 hover:text-orange-800 font-semibold flex items-center gap-2 transition transform hover:scale-105">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <!--Error/Success Messages (replaced by SweetAlert) -->
    <?php if($error): ?>
        <div class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Login Interface -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md">
        <!-- Header with Logo -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 text-center">
            <img src="../images/logo.png" alt="BERMS Logo" class="h-28 w-28 mx-auto mb-4 object-contain">
            <h1 class="text-3xl font-bold">Admin Portal</h1>
            <p class="text-blue-100 mt-2">BERMS Management System</p>
        </div>

        <!-- Login Form -->
        <div class="p-8">
            <h2 class="text-2xl font-bold text-center mb-6 text-blue-800 flex items-center justify-center gap-2">
                <i class="fas fa-lock text-red-600"></i> Secure Login
            </h2>

            <form method="POST" class="space-y-5">
                <div class="relative floating-label">
                    <input type="email" id="email" name="email" placeholder=" " required class="input-field peer">
                    <label for="email" class="absolute left-4 top-2 text-gray-600 transition-all duration-300 text-sm peer-focus:text-blue-600 peer-placeholder-shown:top-4">Email Address</label>
                </div>

                <div class="relative floating-label">
                    <input type="password" id="password" name="password" placeholder=" " required class="input-field peer">
                    <label for="password" class="absolute left-4 top-2 text-gray-600 transition-all duration-300 text-sm peer-focus:text-blue-600 peer-placeholder-shown:top-4">Password</label>
                </div>

                <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center gap-2 mt-6">
                    <i class="fas fa-sign-in-alt"></i>
                    Admin Login
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="text-gray-500 text-sm">Or continue with</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Social Login Buttons -->
            <div class="space-y-3">
                <button type="button" onclick="loginWithGoogle()" class="w-full bg-white border-2 border-gray-300 hover:border-red-500 hover:bg-red-50 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2">
                    <i class="fab fa-google text-red-600 text-lg"></i>
                    Login with Google
                </button>

                <button type="button" onclick="loginWithFacebook()" class="w-full bg-white border-2 border-gray-300 hover:border-blue-600 hover:bg-blue-50 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2">
                    <i class="fab fa-facebook text-blue-600 text-lg"></i>
                    Login with Facebook
                </button>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <p class="text-gray-600 text-sm">
                    <a href="../index.php" class="text-orange-600 hover:text-orange-800 font-semibold">← Back to Home</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Display success notification and redirect
        <?php if($success === "true"): ?>
            window.addEventListener('load', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful!',
                    text: 'Welcome back, Admin!',
                    confirmButtonColor: '#3b82f6',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    window.location.href = 'admin_dashboard.php';
                });
            });
        <?php endif; ?>

        // Display error notification if exists
        <?php if($error): ?>
            window.addEventListener('load', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: '<?php echo htmlspecialchars($error); ?>',
                    confirmButtonColor: '#3b82f6'
                });
            });
        <?php endif; ?>

        function loginWithGoogle() {
            Swal.fire({
                icon: 'info',
                title: 'Coming Soon',
                text: 'Google login integration coming soon!',
                confirmButtonColor: '#3b82f6'
            });
            // In production, integrate with Google OAuth 2.0
            // window.location.href = 'https://accounts.google.com/o/oauth2/auth?...';
        }

        function loginWithFacebook() {
            Swal.fire({
                icon: 'info',
                title: 'Coming Soon',
                text: 'Facebook login integration coming soon!',
                confirmButtonColor: '#3b82f6'
            });
            // In production, integrate with Facebook OAuth
            // window.location.href = 'https://www.facebook.com/v12.0/dialog/oauth?...';
        }

        // Form validation and loading indicator
        const loginForm = document.querySelector('form');
        if(loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const email = document.querySelector('input[name="email"]')?.value.trim() || '';
                const password = document.querySelector('input[name="password"]')?.value.trim() || '';

                if(!email || !password) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Credentials',
                        text: 'Please enter both email and password.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                if(!email.includes('@')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                // Show loading indicator and submit form
                Swal.fire({
                    icon: 'info',
                    title: 'Signing In',
                    html: 'Please wait while we verify your credentials...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form after showing loading indicator
                setTimeout(() => {
                    loginForm.submit();
                }, 100);
            });
        }
    </script>
</body>
</html>

