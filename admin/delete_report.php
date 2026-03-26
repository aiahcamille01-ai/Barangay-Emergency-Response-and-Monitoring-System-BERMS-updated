<?php
session_start();
include("../config/connect.php");

// Check if admin is logged in
if(!isset($_SESSION['email'])){
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

if(isset($_GET['id'])){
    $report_id = (int)$_GET['id'];
    
    // Get the attachment path before deleting
    $sql = "SELECT attachment_path FROM reports WHERE id=$report_id";
    $result = $conn->query($sql);
    $report = $result->fetch_assoc();
    
    if($report){
        // Delete the report from database
        $delete_sql = "DELETE FROM reports WHERE id=$report_id";
        if($conn->query($delete_sql)){
            // Delete the attached image file if it exists
            if($report['attachment_path'] && file_exists('../' . $report['attachment_path'])){
                unlink('../' . $report['attachment_path']);
            }
            
            echo json_encode(['success' => true, 'message' => 'Report deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting report from database']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Report not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Report ID not provided']);
}
?>
