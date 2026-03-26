<?php
session_start();
include("../config/connect.php");

// Check if admin is logged in
if(!isset($_SESSION['email'])){
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get filter parameters
$filter_status = isset($_GET['status']) ? $_GET['status'] : 'All';
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'All';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Build dynamic query
$where_clause = "1=1";
if($filter_status != 'All'){
    $where_clause .= " AND status='" . $conn->real_escape_string($filter_status) . "'";
}
if($filter_type != 'All'){
    $where_clause .= " AND emergency_type='" . $conn->real_escape_string($filter_type) . "'";
}
if($search != ''){
    $where_clause .= " AND (user_name LIKE '%$search%' OR user_email LIKE '%$search%' OR details LIKE '%$search%')";
}

// Get ALL filtered records (no limit for printing)
$sql = "SELECT * FROM reports WHERE $where_clause ORDER BY created_at DESC";
$result = $conn->query($sql);

$reports = [];
while($row = $result->fetch_assoc()){
    $reports[] = [
        'id' => $row['id'],
        'user_name' => htmlspecialchars($row['user_name']),
        'user_email' => htmlspecialchars($row['user_email']),
        'emergency_type' => htmlspecialchars($row['emergency_type']),
        'details' => htmlspecialchars($row['details']),
        'status' => htmlspecialchars($row['status']),
        'created_at' => date('M d, Y', strtotime($row['created_at']))
    ];
}

header('Content-Type: application/json');
echo json_encode(['reports' => $reports]);
?>
