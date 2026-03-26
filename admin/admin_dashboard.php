<?php
session_start();
include("../config/connect.php");

// Check if admin is logged in
if(!isset($_SESSION['email'])){
    header("Location: adminlogin.php");
    exit();
}

// Handle status update
if(isset($_POST['update_status'])){
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];
    $sql = "UPDATE reports SET status='$status' WHERE id=$report_id";
    $conn->query($sql);
}

// Get filter parameters
$filter_status = isset($_GET['status']) ? $_GET['status'] : 'All';
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'All';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter_date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$filter_date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

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
if($filter_date_from != ''){
    $where_clause .= " AND DATE(created_at) >= '" . $conn->real_escape_string($filter_date_from) . "'";
}
if($filter_date_to != ''){
    $where_clause .= " AND DATE(created_at) <= '" . $conn->real_escape_string($filter_date_to) . "'";
}

// Get total filtered records (for pagination)
$total_sql = "SELECT COUNT(*) as total FROM reports WHERE $where_clause";
$total_result = $conn->query($total_sql)->fetch_assoc();
$total_reports = $total_result['total'];

// Pagination setup
$records_per_page = 10;
$total_pages = ceil($total_reports / $records_per_page);
if($page > $total_pages && $total_pages > 0) $page = $total_pages;
if($page < 1) $page = 1;

$offset = ($page - 1) * $records_per_page;

// Get paginated records for display
$sql = "SELECT * FROM reports WHERE $where_clause ORDER BY created_at DESC LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);
$page_reports = $result->num_rows;

// Get summary stats
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status='In Progress' THEN 1 ELSE 0 END) as in_progress,
    SUM(CASE WHEN status='Resolved' THEN 1 ELSE 0 END) as resolved
FROM reports";
$stats = $conn->query($stats_sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BERMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; }
        @media (max-width: 640px) {
            html { font-size: 14px; }
            input, select, button { font-size: 16px !important; }
        }
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg sticky top-0 z-40">
        <div class="w-full mx-auto px-2 sm:px-4 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16 flex-wrap">
                <div class="flex items-center gap-2 sm:gap-3">
                    <img src="../images/logo.png" alt="BERMS Logo" class="h-12 sm:h-16 w-12 sm:w-16 object-contain">
                    <h1 class="text-lg sm:text-2xl font-bold">BERMS Admin</h1>
                </div>
                <div class="flex items-center gap-2 sm:gap-6 flex-wrap justify-end">
                    <span class="text-xs sm:text-sm hidden sm:block">Welcome, <?php echo htmlspecialchars(substr($_SESSION['username'] ?? $_SESSION['email'], 0, 15)); ?></span>
                    <a href="registeradmin.php" class="bg-green-600 hover:bg-green-700 px-2 sm:px-4 py-1 sm:py-2 rounded-lg font-semibold transition text-xs sm:text-sm">
                        <i class="fas fa-user-plus mr-1 sm:mr-2"></i><span class="hidden sm:inline">Register Admin</span><span class="sm:hidden">Add</span>
                    </a>
                    <a href="../pages/logout.php" class="bg-red-600 hover:bg-red-700 px-2 sm:px-4 py-1 sm:py-2 rounded-lg font-semibold transition text-xs sm:text-sm">
                        <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i><span class="hidden sm:inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="w-full mx-auto px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 lg:gap-6 mb-4 sm:mb-6 lg:mb-8">
            <div class="bg-white rounded-lg shadow p-3 sm:p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm font-semibold">Total</p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-600"><?php echo $stats['total']; ?></p>
                    </div>
                    <i class="fas fa-file-alt text-2xl sm:text-4xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 sm:p-6 border-l-4 border-yellow-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm font-semibold">Pending</p>
                        <p class="text-2xl sm:text-3xl font-bold text-yellow-600"><?php echo $stats['pending'] ?? 0; ?></p>
                    </div>
                    <i class="fas fa-clock text-2xl sm:text-4xl text-yellow-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 sm:p-6 border-l-4 border-orange-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm font-semibold">In Progress</p>
                        <p class="text-2xl sm:text-3xl font-bold text-orange-600"><?php echo $stats['in_progress'] ?? 0; ?></p>
                    </div>
                    <i class="fas fa-spinner text-2xl sm:text-4xl text-orange-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 sm:p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm font-semibold">Resolved</p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-600"><?php echo $stats['resolved'] ?? 0; ?></p>
                    </div>
                    <i class="fas fa-check-circle text-2xl sm:text-4xl text-green-200"></i>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow p-3 sm:p-6 mb-2 sm:mb-4 no-print">
            <h2 class="text-lg sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Reports Management</h2>
            
            <form method="GET" class="space-y-2 sm:space-y-3">
                <!-- Action Buttons Row -->
                <div class="flex gap-1 sm:gap-2 mb-2 sm:mb-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition text-xs sm:text-sm flex items-center justify-center gap-1">
                        <i class="fas fa-search"></i><span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="admin_dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition flex items-center justify-center gap-1 text-xs sm:text-sm">
                        <i class="fas fa-redo"></i><span class="hidden sm:inline">Reset</span>
                    </a>
                    <button type="button" onclick="printReport()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition text-xs sm:text-sm flex items-center justify-center gap-1">
                        <i class="fas fa-print"></i><span class="hidden sm:inline">Print</span>
                    </button>
                </div>

                <!-- Filters Row -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3 lg:gap-4">
                    <!-- Search -->
                    <div class="col-span-2 sm:col-span-3 lg:col-span-2">
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Search</label>
                        <input type="text" name="search" placeholder="Name, email..." value="<?php echo htmlspecialchars($search); ?>" class="w-full px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Status</label>
                        <select name="status" class="w-full px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="All">All</option>
                            <option value="Pending" <?php echo $filter_status == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="In Progress" <?php echo $filter_status == 'In Progress' ? 'selected' : ''; ?>>Progress</option>
                            <option value="Resolved" <?php echo $filter_status == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Type</label>
                        <select name="type" class="w-full px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="All">All</option>
                            <option value="Fire" <?php echo $filter_type == 'Fire' ? 'selected' : ''; ?>>Fire</option>
                            <option value="Ambulance" <?php echo $filter_type == 'Ambulance' ? 'selected' : ''; ?>>Medical</option>
                            <option value="Police" <?php echo $filter_type == 'Police' ? 'selected' : ''; ?>>Police</option>
                        </select>
                    </div>

                    <!-- Date From Filter -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">From</label>
                        <input type="date" name="date_from" value="<?php echo htmlspecialchars($filter_date_from); ?>" class="w-full px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Date To Filter -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">To</label>
                        <input type="date" name="date_to" value="<?php echo htmlspecialchars($filter_date_to); ?>" class="w-full px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-800 text-white sticky top-0">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold">ID</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold">Reporter</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold">Type</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold hidden sm:table-cell">Details</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold">Status</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold hidden md:table-cell">Date</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs sm:text-sm font-semibold hidden lg:table-cell">Evidence</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if($page_reports > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-semibold text-gray-900">#<?php echo $row['id']; ?></td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm">
                                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars(substr($row['user_name'], 0, 15)); ?></div>
                                        <div class="text-gray-600 text-xs hidden sm:block"><?php echo htmlspecialchars(substr($row['user_email'], 0, 20)); ?></div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        <span class="inline-block px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold
                                            <?php 
                                                if($row['emergency_type'] == 'Fire') echo 'bg-red-100 text-red-800';
                                                elseif($row['emergency_type'] == 'Ambulance') echo 'bg-green-100 text-green-800';
                                                elseif($row['emergency_type'] == 'Police') echo 'bg-blue-100 text-blue-800';
                                            ?>
                                        ">
                                            <?php echo htmlspecialchars($row['emergency_type']); ?>
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-600 max-w-xs truncate hidden sm:table-cell"><?php echo htmlspecialchars(substr($row['details'], 0, 50)); ?>...</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        <form method="POST" class="flex gap-1 sm:gap-2">
                                            <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="px-2 sm:px-3 py-1 rounded text-xs sm:text-sm font-semibold border
                                                <?php 
                                                    if($row['status'] == 'Pending') echo 'border-yellow-300 bg-yellow-50 text-yellow-800';
                                                    elseif($row['status'] == 'In Progress') echo 'border-orange-300 bg-orange-50 text-orange-800';
                                                    elseif($row['status'] == 'Resolved') echo 'border-green-300 bg-green-50 text-green-800';
                                                ?>
                                            ">
                                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="In Progress" <?php echo $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Resolved" <?php echo $row['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-600 hidden md:table-cell"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-center hidden lg:table-cell">
                                        <?php if($row['attachment_path']): ?>
                                            <span class="inline-block bg-green-100 text-green-800 px-2 sm:px-3 py-1 rounded-full text-xs font-semibold" title="Evidence image attached">
                                                <i class="fas fa-image mr-1"></i>Yes
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-block bg-gray-100 text-gray-600 px-2 sm:px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-times mr-1"></i>No
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        <button onclick="viewReport(<?php echo $row['id']; ?>)" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm font-semibold transition">
                                            <i class="fas fa-eye mr-1"></i><span class="hidden sm:inline">View</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p>No reports found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex flex-col items-center justify-center gap-4 no-print">
            <div class="text-gray-600 text-xs sm:text-sm text-center">
                <span class="font-semibold">Page <?php echo $page; ?> of <?php echo max(1, $total_pages); ?></span> | Showing <?php echo $page_reports; ?> of <?php echo $total_reports; ?> record(s)
            </div>
            
            <!-- Page Numbers -->
            <div class="flex gap-1 sm:gap-2 items-center flex-wrap justify-center">
                <!-- Previous Button -->
                <?php if($page > 1): ?>
                    <a href="?status=<?php echo urlencode($filter_status); ?>&type=<?php echo urlencode($filter_type); ?>&search=<?php echo urlencode($search); ?>&date_from=<?php echo urlencode($filter_date_from); ?>&date_to=<?php echo urlencode($filter_date_to); ?>&page=<?php echo $page - 1; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-2 rounded-lg font-semibold transition text-xs sm:text-sm" title="Previous page">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <!-- Page Number Buttons -->
                <?php 
                    $show_pages = [];
                    $range = 2; // Show current page +/- 2 pages
                    
                    // Always show first page
                    $show_pages[] = 1;
                    
                    // Show pages around current page
                    for($i = max(2, $page - $range); $i <= min($total_pages - 1, $page + $range); $i++){
                        $show_pages[] = $i;
                    }
                    
                    // Always show last page
                    if($total_pages > 1){
                        $show_pages[] = $total_pages;
                    }
                    
                    // Remove duplicates and sort
                    $show_pages = array_unique($show_pages);
                    sort($show_pages);
                    
                    $last_page = 0;
                    foreach($show_pages as $p):
                        // Add ellipsis if there's a gap
                        if($p - $last_page > 1):
                            echo '<span class="text-gray-400 px-2">...</span>';
                        endif;
                        $last_page = $p;
                ?>
                    <?php if($p == $page): ?>
                        <button class="bg-green-600 text-white px-2 sm:px-4 py-2 rounded-lg font-bold transition cursor-default text-xs sm:text-sm">
                            <?php echo $p; ?>
                        </button>
                    <?php else: ?>
                        <a href="?status=<?php echo urlencode($filter_status); ?>&type=<?php echo urlencode($filter_type); ?>&search=<?php echo urlencode($search); ?>&date_from=<?php echo urlencode($filter_date_from); ?>&date_to=<?php echo urlencode($filter_date_to); ?>&page=<?php echo $p; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-2 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm">
                            <?php echo $p; ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <!-- Next Button -->
                <?php if($page < $total_pages): ?>
                    <a href="?status=<?php echo urlencode($filter_status); ?>&type=<?php echo urlencode($filter_type); ?>&search=<?php echo urlencode($search); ?>&date_from=<?php echo urlencode($filter_date_from); ?>&date_to=<?php echo urlencode($filter_date_to); ?>&page=<?php echo $page + 1; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-2 sm:px-3 py-2 rounded-lg font-semibold transition text-xs sm:text-sm" title="Next page">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- View Report Modal -->
    <div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full h-[90vh] sm:h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 sm:p-6 flex items-center justify-between rounded-t-xl">
                <h2 class="text-lg sm:text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    Report Details
                </h2>
                <button onclick="closeReportModal()" class="text-white hover:bg-blue-700 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="reportContent" class="p-4 sm:p-6"></div>
            <div class="bg-gray-50 p-4 sm:p-6 rounded-b-xl flex flex-col sm:flex-row gap-2 sm:gap-4 border-t">
                <button onclick="printSingleReport()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2 text-sm sm:text-base">
                    <i class="fas fa-print"></i><span>Print</span>
                </button>
                <button onclick="deleteReport()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2 text-sm sm:text-base">
                    <i class="fas fa-trash-alt"></i><span>Delete</span>
                </button>
                <button onclick="closeReportModal()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg transition text-sm sm:text-base">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentReportId = null;
        
        function viewReport(reportId) {
            currentReportId = reportId;
            fetch(`get_report.php?id=${reportId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('reportContent').innerHTML = html;
                    document.getElementById('reportModal').classList.remove('hidden');
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error loading report: ' + error
                    });
                });
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            currentReportId = null;
        }

        function deleteReport() {
            if(!currentReportId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'No report selected'
                });
                return;
            }
            
            Swal.fire({
                title: 'Delete Report?',
                text: 'Are you sure you want to delete this report? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if(result.isConfirmed) {
                    fetch(`delete_report.php?id=${currentReportId}`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Report has been deleted successfully.',
                                    confirmButtonColor: '#3b82f6'
                                }).then(() => {
                                    closeReportModal();
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error deleting report: ' + data.message
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error: ' + error
                            });
                        });
                }
            });
        }

        function printReport() {
            const printWindow = window.open('', '', 'height=600,width=1000');
            const title = 'BERMS - Emergency Reports Dashboard';
            const timestamp = new Date().toLocaleString();
            
            // Get current filter values
            const searchValue = document.querySelector('input[name="search"]').value;
            const statusValue = document.querySelector('select[name="status"]').value;
            const typeValue = document.querySelector('select[name="type"]').value;
            
            let filterInfo = 'Filters Applied: ';
            if(searchValue) filterInfo += `Search: "${searchValue}" | `;
            if(statusValue !== 'All') filterInfo += `Status: ${statusValue} | `;
            if(typeValue !== 'All') filterInfo += `Type: ${typeValue} | `;
            if(filterInfo === 'Filters Applied: ') filterInfo = 'No filters applied';
            
            // Fetch ALL filtered records from server for printing
            const params = new URLSearchParams();
            params.append('status', statusValue);
            params.append('type', typeValue);
            params.append('search', searchValue);
            params.append('export_all', '1');
            
            fetch('get_all_reports.php?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    let tableHtml = `<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                        <thead>
                            <tr style="background-color: #1e40af; color: white;">
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">ID</th>
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">Reporter</th>
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">Email</th>
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">Type</th>
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">Details</th>
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">Status</th>
                                <th style="padding: 10px; text-align: left; border: 1px solid #ddd; font-size: 12px;">Date</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    
                    data.reports.forEach((report, index) => {
                        const bgColor = index % 2 === 0 ? '#ffffff' : '#f9fafb';
                        tableHtml += `<tr style="background-color: ${bgColor};">
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">#${report.id}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">${report.user_name}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">${report.user_email}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">${report.emergency_type}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">${report.details.substring(0, 50)}...</td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">${report.status}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px;">${report.created_at}</td>
                        </tr>`;
                    });
                    
                    tableHtml += '</tbody></table>';
                    
                    let html = `<html><head><title>BERMS Reports</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { text-align: center; color: #1e40af; }
                        .info { text-align: center; color: #666; margin-bottom: 10px; font-size: 12px; }
                        .timestamp { text-align: center; color: #666; margin-bottom: 20px; font-size: 12px; }
                    </style>
                    </head><body>`;
                    
                    html += `<h1>${title}</h1>`;
                    html += `<div class="info">${filterInfo}</div>`;
                    html += `<div class="timestamp">Generated on: ${timestamp}</div>`;
                    html += `<div class="timestamp">Total Reports: ${data.reports.length}</div>`;
                    html += tableHtml;
                    html += '<p style="margin-top: 20px; font-size: 12px; text-align: center; color: #999;">This is a confidential document. BERMS System</p>';
                    html += '</body></html>';
                    
                    printWindow.document.write(html);
                    printWindow.document.close();
                    setTimeout(() => {
                        printWindow.print();
                    }, 250);
                })
                .catch(error => {
                    console.error('Error:', error);
                    printWindow.close();
                });
        }

        function printSingleReport() {
            const content = document.getElementById('reportContent').innerHTML;
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Report</title></head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // Close modal when clicking outside
        document.getElementById('reportModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });
    </script>
</body>
</html>
