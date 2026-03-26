
<?php
session_start();
include("../config/connect.php");

// Handle report submission via AJAX
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_report'])){
    $emergency_type = $_POST['emergency_type'] ?? 'Unknown';
    $details = $_POST['details'] ?? '';
    $user_name = $_POST['user_name'] ?? 'Anonymous';
    $user_email = $_POST['user_email'] ?? '';
    $attachment_path = '';

    // Handle file upload
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0){
        $targetDir = "../uploads/reports/";
        if(!is_dir($targetDir)){
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES['attachment']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is an image or video
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi');
        if(in_array($fileType, $allowedTypes)){
            if(move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)){
                $attachment_path = "uploads/reports/" . $fileName; // Store web-accessible path
            }
        }
    }

    // Insert into database
    $sql = "INSERT INTO reports(user_name, user_email, emergency_type, details, attachment_path, status)
    VALUES('$user_name', '$user_email', '$emergency_type', '$details', '$attachment_path', 'Pending')";
    
    if($conn->query($sql) == TRUE){
        echo json_encode(['success' => true, 'message' => 'Report submitted successfully!', 'report_id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BERMS - Emergency Report Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; }
    
    .emergency-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 18px;
    }
    
    .badge-fire {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    
    .badge-ambulance {
      background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
    }
    
    .badge-police {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    
    .form-section {
      padding: 20px;
      border-radius: 12px;
      background: #f9fafb;
      border: 1px solid #e5e7eb;
    }
    
    .form-section-title {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
      font-size: 16px;
      font-weight: 600;
      color: #374151;
    }
    
    .form-section-title i {
      font-size: 20px;
      color: #3b82f6;
    }
    
    @media (max-width: 640px) {
      html { font-size: 14px; }
      input, select, textarea, button { font-size: 16px !important; }
    }
  </style>
</head>
<body class="bg-gray-50">
  <!-- Back Navigation -->
  <div class="bg-white shadow">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <a href="../index.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Home
      </a>
    </div>
  </div>

  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <!-- Emergency Type Header -->
      <div class="mb-8">
        <div class="text-center mb-6">
          <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Emergency Report Form</h1>
          <p class="text-gray-600">Please provide detailed information about the emergency</p>
        </div>
        
        <div id="emergencyTypeDisplay" class="text-center">
          <div id="emergencyBadge" class="emergency-badge inline-flex">
            <i id="emergencyIcon" class="fas fa-exclamation-circle"></i>
            <span id="emergencyText">Emergency Report</span>
          </div>
        </div>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <form class="space-y-6 p-6 sm:p-8" id="reportForm" onsubmit="handleSubmit(event)">
          
          <!-- User Information Section -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="fas fa-user-circle"></i>
              <span>Your Information</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
              <div>
                <label for="userName" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                <div class="relative">
                  <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                  <input type="text" id="userName" placeholder="Enter your full name" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                </div>
              </div>
              <div>
                <label for="userEmail" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                  <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                  <input type="email" id="userEmail" placeholder="Enter your email" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Emergency Type Section -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="fas fa-exclamation-circle"></i>
              <span>Emergency Type</span>
            </div>
            <div>
              <label for="emergencyTypeField" class="block text-sm font-semibold text-gray-700 mb-2">Selected Emergency Type <span class="text-red-500">*</span></label>
              <input type="text" id="emergencyTypeField" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" readonly>
            </div>
          </div>

          <!-- Emergency Details Section -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="fas fa-alert-circle"></i>
              <span>Emergency Details</span>
            </div>
            <div>
              <label for="comments" class="block text-sm font-semibold text-gray-700 mb-2">Describe the Emergency <span class="text-red-500">*</span></label>
              <div class="relative">
                <textarea id="comments" placeholder="Provide clear and detailed information about the emergency situation..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none" rows="6" required></textarea>
              </div>
              <p class="text-xs text-gray-500 mt-2">Tip: Include location, number of people involved, and severity</p>
            </div>
          </div>

          <!-- Evidence Section -->
          <div class="form-section">
            <div class="form-section-title">
              <i class="fas fa-image"></i>
              <span>Attach Evidence</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 sm:p-8 text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer" onclick="document.getElementById('attachment').click()">
              <i class="fas fa-cloud-upload-alt text-5xl text-gray-300 mb-3 block"></i>
              <input type="file" id="attachment" class="hidden" accept="image/*,video/*">
              <p id="fileName" class="text-gray-700 font-semibold">Click to upload or drag and drop</p>
              <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF, MP4 up to 10MB</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
            <button type="button" onclick="sendSMS()" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
              <i class="fas fa-mobile-alt"></i>
              <span class="hidden sm:inline">Send SMS</span>
              <span class="sm:hidden">SMS</span>
            </button>
            <button type="button" onclick="sendEmail()" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
              <i class="fas fa-envelope"></i>
              <span class="hidden sm:inline">Send Email</span>
              <span class="sm:hidden">Email</span>
            </button>
            <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl text-lg">
              <i class="fas fa-check-circle"></i>
              <span>SUBMIT</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Info Box -->
      <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <p class="text-sm text-blue-800"><strong>Important:</strong> For life-threatening emergencies, please call your local emergency hotline immediately instead of using this form.</p>
      </div>
    </div>
  </div>

  <!-- SMS Modal -->
  <div id="smsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
      <div class="bg-blue-600 text-white p-6 flex items-center justify-between rounded-t-xl">
        <h2 class="text-2xl font-bold flex items-center gap-2">
          <i class="fas fa-sms"></i>
          Emergency Hotline Numbers
        </h2>
        <button onclick="closeSMSModal()" class="text-white hover:bg-blue-700 rounded-full w-8 h-8 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="p-6 space-y-4">
        <div class="border-l-4 border-blue-600 pl-4">
          <p class="font-semibold text-gray-800">Barangay Captain</p>
          <p class="text-blue-600 font-bold text-lg">+63 917-123-4567</p>
          <p class="text-sm text-gray-600">Brgy. Captain John Dela Cruz</p>
        </div>
        <div class="border-l-4 border-red-600 pl-4">
          <p class="font-semibold text-gray-800">Emergency Response Team</p>
          <p class="text-red-600 font-bold text-lg">+63 917-234-5678</p>
          <p class="text-sm text-gray-600">Barangay Emergency Hotline</p>
        </div>
        <div class="border-l-4 border-orange-600 pl-4">
          <p class="font-semibold text-gray-800">Barangay Tanod Commander</p>
          <p class="text-orange-600 font-bold text-lg">+63 917-345-6789</p>
          <p class="text-sm text-gray-600">Tanod Security Chief</p>
        </div>
        <div class="border-l-4 border-green-600 pl-4">
          <p class="font-semibold text-gray-800">Health Officer</p>
          <p class="text-green-600 font-bold text-lg">+63 917-456-7890</p>
          <p class="text-sm text-gray-600">Medical Emergency</p>
        </div>
      </div>
      <div class="bg-gray-50 p-6 rounded-b-xl flex gap-4">
        <button onclick="closeSMSModal()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
          Close
        </button>
        <button onclick="confirmSMS()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2">
          <i class="fas fa-check"></i>
          Confirmed
        </button>
      </div>
    </div>
  </div>

  <!-- Email Modal -->
  <div id="emailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
      <div class="bg-orange-600 text-white p-6 flex items-center justify-between rounded-t-xl">
        <h2 class="text-2xl font-bold flex items-center gap-2">
          <i class="fas fa-envelope"></i>
          Emergency Department Emails
        </h2>
        <button onclick="closeEmailModal()" class="text-white hover:bg-orange-700 rounded-full w-8 h-8 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="p-6 space-y-4">
        <div class="border-l-4 border-blue-600 pl-4">
          <p class="font-semibold text-gray-800">Barangay Captain</p>
          <p class="text-blue-600 font-bold break-all">captain@barangay171.gov.ph</p>
          <p class="text-sm text-gray-600">Brgy. Captain Office</p>
        </div>
        <div class="border-l-4 border-red-600 pl-4">
          <p class="font-semibold text-gray-800">Emergency Response Team</p>
          <p class="text-red-600 font-bold break-all">emergency@barangay171.gov.ph</p>
          <p class="text-sm text-gray-600">Main Emergency Email</p>
        </div>
        <div class="border-l-4 border-orange-600 pl-4">
          <p class="font-semibold text-gray-800">Barangay Tanod</p>
          <p class="text-orange-600 font-bold break-all">tanod@barangay171.gov.ph</p>
          <p class="text-sm text-gray-600">Security & Safety</p>
        </div>
        <div class="border-l-4 border-green-600 pl-4">
          <p class="font-semibold text-gray-800">Health & Wellness</p>
          <p class="text-green-600 font-bold break-all">health@barangay171.gov.ph</p>
          <p class="text-sm text-gray-600">Medical Emergencies</p>
        </div>
      </div>
      <div class="bg-gray-50 p-6 rounded-b-xl flex gap-4">
        <button onclick="closeEmailModal()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
          Close
        </button>
        <button onclick="confirmEmail()" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2">
          <i class="fas fa-check"></i>
          Confirmed
        </button>
      </div>
    </div>
  </div>

  <script>
    // Set emergency type with enhanced styling
    const emergencyTypeRaw = localStorage.getItem("emergencyType") || "Unknown";
    const emergencyType = emergencyTypeRaw.trim();
    const badge = document.getElementById("emergencyBadge");
    const icon = document.getElementById("emergencyIcon");
    const text = document.getElementById("emergencyText");
    
    text.innerHTML = emergencyType + " Emergency";

    const typeKey = emergencyType.toLowerCase();
    if (typeKey === 'fire') {
      badge.className = 'emergency-badge badge-fire';
      icon.className = 'fas fa-fire';
    } else if (typeKey === 'ambulance') {
      badge.className = 'emergency-badge badge-ambulance';
      icon.className = 'fas fa-ambulance';
    } else if (typeKey === 'police') {
      badge.className = 'emergency-badge badge-police';
      icon.className = 'fas fa-shield';
    } else {
      badge.className = 'emergency-badge';
      icon.className = 'fas fa-exclamation-circle';
    }
    
    // Set emergency type in form field
    document.getElementById('emergencyTypeField').value = emergencyType;

    // File upload handler
    document.getElementById("attachment").addEventListener('change', function(e) {
      const file = e.target.files[0];
      if(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        if(file.size > maxSize) {
          Swal.fire({
            icon: 'error',
            title: 'File Too Large',
            text: 'File size exceeds 10MB limit. Please choose a smaller file.',
            confirmButtonColor: '#3b82f6'
          });
          document.getElementById("attachment").value = '';
          document.getElementById("fileName").textContent = 'Click to upload or drag and drop';
          return;
        }
        const fileName = file.name;
        document.getElementById("fileName").textContent = fileName;
        Swal.fire({
          icon: 'success',
          title: 'File Selected',
          text: 'File "' + fileName + '" has been attached successfully!',
          timer: 2000,
          showConfirmButton: false,
          confirmButtonColor: '#3b82f6'
        });
      }
    });

    // Drag and drop
    const uploadArea = document.querySelector('[accept="image/*,video/*"]').parentElement;
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    uploadArea.addEventListener('click', () => document.getElementById('attachment').click());

    // Drop file handling
    uploadArea.addEventListener('drop', function(e) {
      const dt = e.dataTransfer;
      const files = dt.files;
      document.getElementById('attachment').files = files;
      const fileName = files[0]?.name || 'Click to upload or drag and drop';
      document.getElementById("fileName").textContent = fileName;
    });

    function sendSMS(){
      document.getElementById('smsModal').classList.remove('hidden');
    }

    function closeSMSModal(){
      document.getElementById('smsModal').classList.add('hidden');
    }

    function confirmSMS(){
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'SMS Sent to Emergency Hotline',
        confirmButtonColor: '#3b82f6'
      });
      closeSMSModal();
    }

    function sendEmail(){
      document.getElementById('emailModal').classList.remove('hidden');
    }

    function closeEmailModal(){
      document.getElementById('emailModal').classList.add('hidden');
    }

    function confirmEmail(){
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Email Sent to Emergency Department',
        confirmButtonColor: '#3b82f6'
      });
      closeEmailModal();
    }

    function handleSubmit(e) {
      e.preventDefault();
      
      const userName = document.getElementById('userName').value;
      const userEmail = document.getElementById('userEmail').value;
      const comments = document.getElementById('comments').value;
      const attachment = document.getElementById('attachment').files[0];
      const type = localStorage.getItem("emergencyType") || 'Unknown';

      if (!userName.trim() || !userEmail.trim() || !comments.trim()) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Information',
          text: 'Please fill in all required fields.',
          confirmButtonColor: '#3b82f6'
        });
        return;
      }

      const formData = new FormData();
      formData.append('submit_report', '1');
      formData.append('user_name', userName);
      formData.append('user_email', userEmail);
      formData.append('details', comments);
      formData.append('emergency_type', type);
      if(attachment) {
        formData.append('attachment', attachment);
      }

      fetch(window.location.href, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Report Submitted!',
            html: 'Emergency Report Submitted Successfully!<br><br>Your report number: <strong>#' + data.report_id + '</strong>',
            confirmButtonColor: '#3b82f6'
          }).then(() => {
            window.location = '../index.php';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error: ' + data.message,
            confirmButtonColor: '#3b82f6'
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to submit report. Please try again.',
          confirmButtonColor: '#3b82f6'
        });
      });
    }

    // Close modals when clicking outside
    document.getElementById('smsModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeSMSModal();
      }
    });

    document.getElementById('emailModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeEmailModal();
      }
    });
  </script>
</body>
</html>