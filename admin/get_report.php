<?php
include("../config/connect.php");

if(isset($_GET['id'])){
    $report_id = $_GET['id'];
    $sql = "SELECT * FROM reports WHERE id=$report_id";
    $result = $conn->query($sql);
    $report = $result->fetch_assoc();
    
    if($report){
        ?>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Report ID</p>
                    <p class="text-lg font-bold text-gray-900">#<?php echo $report['id']; ?></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Date/Time</p>
                    <p class="text-lg font-bold text-gray-900"><?php echo date('M d, Y H:i', strtotime($report['created_at'])); ?></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Status</p>
                    <p class="text-lg font-bold 
                        <?php 
                            if($report['status'] == 'Pending') echo 'text-yellow-600';
                            elseif($report['status'] == 'In Progress') echo 'text-orange-600';
                            elseif($report['status'] == 'Resolved') echo 'text-green-600';
                        ?>
                    "><?php echo $report['status']; ?></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Emergency Type</p>
                    <p class="text-lg font-bold text-gray-900"><?php echo $report['emergency_type']; ?></p>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm font-semibold text-gray-600">Reporter Information</p>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900"><strong>Name:</strong> <?php echo htmlspecialchars($report['user_name']); ?></p>
                    <p class="text-gray-900"><strong>Email:</strong> <?php echo htmlspecialchars($report['user_email']); ?></p>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm font-semibold text-gray-600">Emergency Details</p>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg max-h-48 overflow-y-auto">
                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($report['details']); ?></p>
                </div>
            </div>

            <?php if($report['attachment_path']): ?>
                <div class="border-t pt-4">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Evidence Photo</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <img src="../<?php echo htmlspecialchars($report['attachment_path']); ?>" alt="Evidence" class="max-w-full h-auto rounded-lg shadow-md max-h-80 mb-3">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-image mr-1"></i><?php echo htmlspecialchars(basename($report['attachment_path'])); ?>
                        </p>
                        <a href="../<?php echo htmlspecialchars($report['attachment_path']); ?>" target="_blank" class="inline-block mt-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            <i class="fas fa-external-link-alt mr-1"></i>View Full Image
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>
