<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "incident_system";

$conn = new mysqli($host, $user, $password, $dbname);

// Don't die immediately - let the calling script handle connection errors
// if($conn->connect_error){
//     die("Connection failed: " . $conn->connect_error);
// }

?>
