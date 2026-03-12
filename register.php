<?php
session_start();
include("connect.php");


/* REGISTER */
if(isset($_POST['signUp'])){

$fName = $_POST['fName'];
$lName = $_POST['lName'];
$email = $_POST['email'];
$password = $_POST['password'];

$checkEmail = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($checkEmail);

if($result->num_rows > 0){
    echo "Email already exists!";
}else{

$sql = "INSERT INTO users(firstName,lastName,email,password)
VALUES('$fName','$lName','$email','$password')";

if($conn->query($sql)==TRUE){
    echo "Registration successful";
}else{
    echo "Error: ".$conn->error;
}

}

}


/* LOGIN */
if(isset($_POST['signIn'])){

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if($result->num_rows > 0){

$row = $result->fetch_assoc();

$_SESSION['email'] = $row['email'];
$_SESSION['name'] = $row['firstName'];

header("Location: userpage1.php");
exit();

}else{

echo "Incorrect Email or Password";

}

}

?>