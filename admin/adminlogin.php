<?php
session_start();
include("../connect.php");

if(isset($_POST['adminSignIn'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        $_SESSION['admin_email'] = $row['email'];
        $_SESSION['admin_name'] = $row['name'];

        header("Location: admindashboard.php");
        exit();

    }else{
        echo "Incorrect Admin Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BERMS Admin Login</title>
</head>
<body>

    <h2>Admin Login</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Admin Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="adminSignIn">Login</button>
    </form>

</body>
</html>
