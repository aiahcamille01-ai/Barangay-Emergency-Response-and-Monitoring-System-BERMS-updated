<?php
session_start();
include("../config/connect.php");

// Check if admin is logged in
if(!isset($_SESSION['email'])){
    header("Location: adminlogin.php");
    exit();
}

$error = '';
$success = '';

if(isset($_POST['register'])){
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
    
    // Validation
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($birthday)){
        $error = "All fields are required!";
    } elseif(strlen($username) < 3){
        $error = "Username must be at least 3 characters long!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email format!";
    } elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters long!";
    } elseif($password !== $confirm_password){
        $error = "Passwords do not match!";
    } elseif(!isset($_FILES['id_picture']) || $_FILES['id_picture']['error'] == UPLOAD_ERR_NO_FILE){
        $error = "ID picture is required!";
    } else {
        // Handle file upload
        $upload_dir = '../admin_uploads/';
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0755, true);
        }
        
        $file = $_FILES['id_picture'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if($file['size'] > $max_size){
            $error = "File size must not exceed 5MB!";
        } elseif(!in_array($file['type'], $allowed_types)){
            $error = "Only JPG and PNG files are allowed!";
        } else {
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = $username . '_' . time() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if(move_uploaded_file($file['tmp_name'], $file_path)){
                // Check if username already exists
                $check_sql = "SELECT * FROM admin WHERE username='$username' OR email='$email'";
                $check_result = $conn->query($check_sql);
                
                if($check_result->num_rows > 0){
                    $error = "Username or email already exists!";
                    unlink($file_path); // Delete uploaded file
                } else {
                    // Insert new admin with ID picture and birthday
                    $id_picture = $conn->real_escape_string($file_name);
                    $birthday = $conn->real_escape_string($birthday);
                    $insert_sql = "INSERT INTO admin (username, email, password, id_picture, birthday) VALUES ('$username', '$email', '$password', '$id_picture', '$birthday')";
                    if($conn->query($insert_sql)){
                        $success = "Admin account created successfully! <a href='admin_dashboard.php' class='text-white underline'>Go back to dashboard</a>";
                    } else {
                        $error = "Error creating admin account. Please try again!";
                        unlink($file_path);
                    }
                }
            } else {
                $error = "Error uploading file. Please try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - BERMS</title>
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
            position: absolute;
            left: 16px;
            top: 16px;
            font-size: 14px;
            color: #9ca3af;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        .input-field:focus ~ label,
        .input-field:not(:placeholder-shown) ~ label {
            transform: translateY(-1.5rem);
            color: #3498db;
            font-size: 12px;
        }
        .file-input-label {
            position: relative;
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-blue-50 to-orange-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-8 text-center">
                    <img src="../images/logo.png" alt="BERMS Logo" class="h-20 w-20 object-contain mx-auto mb-4">
                    <h1 class="text-3xl font-bold text-white mb-2">BERMS Admin</h1>
                    <p class="text-blue-100">Register New Admin Account</p>
                </div>

                <!-- Form -->
                <div class="px-8 py-8">
                    <?php if($error): ?>
                        <div class="hidden mb-6 p-4 bg-red-50 border-l-4 border-red-600 rounded">
                            <p class="text-red-700 font-semibold">
                                <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if($success): ?>
                        <div class="hidden mb-6 p-4 bg-green-50 border-l-4 border-green-600 rounded">
                            <p class="text-green-700 font-semibold">
                                <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Username -->
                        <div class="relative floating-label">
                            <input 
                                type="text" 
                                name="username" 
                                placeholder=" "
                                class="input-field peer"
                                required
                            >
                            <label for="username">Username</label>
                        </div>

                        <!-- Email -->
                        <div class="relative floating-label">
                            <input 
                                type="email" 
                                name="email" 
                                placeholder=" "
                                class="input-field peer"
                                required
                            >
                            <label for="email">Email Address</label>
                        </div>

                        <!-- Password -->
                        <div class="relative floating-label">
                            <input 
                                type="password" 
                                name="password" 
                                placeholder=" "
                                class="input-field peer"
                                required
                            >
                            <label for="password">Password (min. 6 characters)</label>
                        </div>

                        <!-- Confirm Password -->
                        <div class="relative floating-label">
                            <input 
                                type="password" 
                                name="confirm_password" 
                                placeholder=" "
                                class="input-field peer"
                                required
                            >
                            <label for="confirm_password">Confirm Password</label>
                        </div>

                        <!-- Birthday -->
                        <div class="relative floating-label">
                            <input 
                                type="date" 
                                name="birthday" 
                                placeholder=" "
                                class="input-field peer"
                                required
                            >
                            <label for="birthday">Birthday</label>
                        </div>

                        <!-- ID Picture -->
                        <div>
                            <label class="file-input-label">ID Picture (JPG/PNG, max 5MB)</label>
                            <input 
                                type="file" 
                                name="id_picture" 
                                accept="image/jpeg,image/png"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                                required
                            >
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="register" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 rounded-lg transition duration-300 shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Create Admin Account
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-6 text-center">
                        <a href="admin_dashboard.php" class="text-blue-600 hover:text-blue-800 font-semibold transition">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Note -->
            <div class="mt-6 p-4 bg-white rounded-lg shadow text-center text-sm text-gray-600">
                <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                Only logged-in admins can access this page
            </div>
        </div>
    </div>

    <script>
        // Display error notification if exists
        <?php if($error): ?>
            window.addEventListener('load', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Error',
                    text: '<?php echo htmlspecialchars($error); ?>',
                    confirmButtonColor: '#3b82f6'
                });
            });
        <?php endif; ?>

        // Display success notification if exists
        <?php if($success): ?>
            window.addEventListener('load', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Admin Created Successfully!',
                    text: 'The new admin account has been registered.',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    window.location.href = 'admin_dashboard.php';
                });
            });
        <?php endif; ?>

        // Form validation
        const form = document.querySelector('form');
        if(form) {
            form.addEventListener('submit', function(e) {
                const username = document.querySelector('input[name="username"]')?.value.trim() || '';
                const email = document.querySelector('input[name="email"]')?.value.trim() || '';
                const password = document.querySelector('input[name="password"]')?.value.trim() || '';
                const confirm_password = document.querySelector('input[name="confirm_password"]')?.value.trim() || '';
                const birthday = document.querySelector('input[name="birthday"]')?.value.trim() || '';
                const idPicture = document.querySelector('input[name="id_picture"]');

                if(!username || !email || !password || !confirm_password || !birthday) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                if(username.length < 3) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Username Too Short',
                        text: 'Username must be at least 3 characters long.',
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

                if(password.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Weak Password',
                        text: 'Password must be at least 6 characters long.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                if(password !== confirm_password) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Don\'t Match',
                        text: 'Password and confirm password must be the same.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                if(!idPicture || !idPicture.files || idPicture.files.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing ID Picture',
                        text: 'Please upload an ID picture.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                const file = idPicture.files[0];
                const maxSize = 5 * 1024 * 1024;
                if(file.size > maxSize) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'ID picture must not exceed 5MB.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if(!allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Only JPG and PNG files are allowed for ID picture.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }
            });
        }
    </script>
</body>
</html>
