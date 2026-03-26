<?php
session_start();
include("../config/connect.php");

/* REGISTER */
if(isset($_POST['signUp'])){
    // Check database connection first
    if($conn->connect_error){
        $errorMsg = "Database connection failed. Please try again later.";
    } else {
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $birthday = $_POST['birthday'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Handle file upload
        $idPicture = "";
        if(isset($_FILES['idPicture']) && $_FILES['idPicture']['error'] == 0){
            $targetDir = "../uploads/admins/";
            if(!is_dir($targetDir)){
                mkdir($targetDir, 0777, true);
            }
            $fileName = uniqid() . "_" . basename($_FILES['idPicture']['name']);
            $targetFile = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if file is an image
            $allowedTypes = array('jpg', 'jpeg', 'png');
            if(in_array($fileType, $allowedTypes)){
                if(move_uploaded_file($_FILES['idPicture']['tmp_name'], $targetFile)){
                    $idPicture = "uploads/admins/" . $fileName; // Store web-accessible path
                } else {
                    $errorMsg = "Error uploading ID picture.";
                }
            } else {
                $errorMsg = "Only JPG, JPEG, and PNG files are allowed.";
            }
        }

        if(!isset($errorMsg)){
            $checkEmail = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($checkEmail);

            if($result->num_rows > 0){
                $errorMsg = "Email already exists!";
            }else{
                $sql = "INSERT INTO users(firstName,lastName,birthday,email,password,idPicture)
                VALUES('$fName','$lName','$birthday','$email','$password','$idPicture')";

                if($conn->query($sql)==TRUE){
                    $successMsg = "Registration successful! Please sign in now.";
                }else{
                    $errorMsg = "Error: ".$conn->error;
                }
            }
        }
    }
}

/* LOGIN */
if(isset($_POST['signIn'])){
    // Check database connection first
    if($conn->connect_error){
        $errorMsg = "Database connection failed. Please try again later.";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['firstName'];
            // Check if user came from emergency reporting
            if(isset($_GET['emergency']) && $_GET['emergency'] == 'true'){
                header("Location: ./reportform.php");
            } else {
                header("Location: ./userpage1.php");
            }
            exit();
        }else{
            $errorMsg = "Incorrect Email or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERMS - User Login & Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">    <script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>    <style>
        body { font-family: 'Poppins', sans-serif; }
        .input-field {
            width: 100%;
            padding: 12px 16px;
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
        .floating-label input:focus ~ label,
        .floating-label input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.5rem);
            font-size: 0.875rem;
            color: #3498db;
        }
        .floating-label label {
            transition: all 0.3s ease;
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
    <?php if(isset($errorMsg)): ?>
        <div class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $errorMsg; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($successMsg)): ?>
        <div class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <i class="fas fa-check-circle mr-2"></i><?php echo $successMsg; ?>
        </div>
    <?php endif; ?>

    <!-- Login/Register Interface -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md">
        <!-- Register Container -->
        <div class="form-container hidden p-8" id="signup">
            <a href="../index.php" class="text-orange-600 hover:text-orange-800 text-sm font-semibold mb-4 inline-flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Home
            </a>
            <h1 class="text-3xl font-bold text-center mb-6 text-blue-800 flex items-center justify-center gap-2"><i class="fas fa-shield-alt text-red-600"></i> Create Account</h1>
            <form method="post" action="" enctype="multipart/form-data" class="space-y-6">
                <div class="floating-label relative">
                    <input type="text" name="fName" id="fName" placeholder=" " required class="input-field peer">
                    <label for="fName" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">First Name</label>
                </div>
                <div class="floating-label relative">
                    <input type="text" name="lName" id="lName" placeholder=" " required class="input-field peer">
                    <label for="lName" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">Last Name</label>
                </div>
                <div class="floating-label relative">
                    <input type="date" name="birthday" id="birthday" required class="input-field peer">
                    <label for="birthday" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">Birthday</label>
                </div>
                <div class="floating-label relative">
                    <input type="email" name="email" id="email" placeholder=" " required class="input-field peer">
                    <label for="email" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">Email</label>
                </div>
                <div class="floating-label relative">
                    <input type="password" name="password" id="password" placeholder=" " required class="input-field peer">
                    <label for="password" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">Password</label>
                </div>
                <div>
                    <label for="idPicture" class="block text-sm font-semibold text-gray-700 mb-2">ID Picture (JPG, PNG)</label>
                    <input type="file" name="idPicture" id="idPicture" accept="image/*" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" name="signUp" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                    Sign Up
                </button>
            </form>
            <div class="text-center mt-6">
                <p class="text-gray-600">Already have an account?</p>
                <button type="button" id="signInButton" class="text-orange-600 font-semibold hover:text-orange-800 hover:underline">Sign In</button>
            </div>
        </div>

        <!-- Login Container -->
        <div class="form-container p-8" id="signIn">
            <a href="../index.php" class="text-orange-600 hover:text-orange-800 text-sm font-semibold mb-4 inline-flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Home
            </a>
            <h1 class="text-3xl font-bold text-center mb-6 text-blue-800 flex items-center justify-center gap-2"><i class="fas fa-shield-alt text-red-600"></i> Welcome Back</h1>
            <form method="post" action="" class="space-y-6">
                <div class="floating-label relative">
                    <input type="email" name="email" id="signinEmail" placeholder=" " required class="input-field peer">
                    <label for="signinEmail" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">Email</label>
                </div>
                <div class="floating-label relative">
                    <input type="password" name="password" id="signinPassword" placeholder=" " required class="input-field peer">
                    <label for="signinPassword" class="absolute left-4 top-2 text-gray-600 peer-placeholder-shown:top-3.5 peer-focus:top-2">Password</label>
                </div>
                <button type="submit" name="signIn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                    Sign In
                </button>
            </form>
            <div class="text-center mt-6">
                <p class="text-gray-600">Don't have an account?</p>
                <button type="button" id="signUpButton" class="text-orange-600 font-semibold hover:text-orange-800 hover:underline">Sign Up</button>
            </div>
        </div>
    </div>

    <script>
        // Display error notification if exists
        <?php if(isset($errorMsg)): ?>
            window.addEventListener('load', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo htmlspecialchars($errorMsg); ?>',
                    confirmButtonColor: '#3b82f6'
                });
            });
        <?php endif; ?>

        // Display success notification if exists
        <?php if(isset($successMsg)): ?>
            window.addEventListener('load', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo htmlspecialchars($successMsg); ?>',
                    confirmButtonColor: '#3b82f6'
                });
            });
        <?php endif; ?>

        const signUpButton = document.getElementById('signUpButton');
        const signInButton = document.getElementById('signInButton');
        const signUpContainer = document.getElementById('signup');
        const signInContainer = document.getElementById('signIn');

        if (signUpButton && signInButton) {
            signUpButton.addEventListener('click', () => {
                signUpContainer.classList.remove('hidden');
                signInContainer.classList.add('hidden');
            });

            signInButton.addEventListener('click', () => {
                signInContainer.classList.remove('hidden');
                signUpContainer.classList.add('hidden');
            });
        }

        // Form validation with notifications
        const signUpForm = document.querySelector('#signup form');
        const signInForm = document.querySelector('#signIn form');

        if(signUpForm) {
            signUpForm.addEventListener('submit', function(e) {
                const fName = document.querySelector('#signup input[name="fName"]')?.value.trim() || '';
                const lName = document.querySelector('#signup input[name="lName"]')?.value.trim() || '';
                const email = document.querySelector('#signup input[name="email"]')?.value.trim() || '';
                const password = document.querySelector('#signup input[name="password"]')?.value.trim() || '';
                const birthday = document.querySelector('#signup input[name="birthday"]')?.value.trim() || '';

                if(!fName || !lName || !email || !password || !birthday) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill in all required fields.',
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

                const idPicture = document.querySelector('#signup input[type="file"]');
                if(idPicture && idPicture.files.length > 0) {
                    const file = idPicture.files[0];
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if(!allowedTypes.includes(file.type)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Only JPG, JPEG, and PNG files are allowed for ID picture.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return false;
                    }
                    if(file.size > 5 * 1024 * 1024) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'ID picture must not exceed 5MB.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return false;
                    }
                }
            });
        }

        if(signInForm) {
            signInForm.addEventListener('submit', function(e) {
                const email = document.querySelector('#signIn input[name="email"]')?.value.trim() || '';
                const password = document.querySelector('#signIn input[name="password"]')?.value.trim() || '';

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
            });
        }
    </script>
</body>
</html>
