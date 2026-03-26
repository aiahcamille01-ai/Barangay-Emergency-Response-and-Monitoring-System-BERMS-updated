<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERMS - Barangay 171 Emergency Response System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center gap-2 sm:gap-3">
                    <img src="images/logo.png" alt="BERMS Logo" class="h-14 w-14 sm:h-20 sm:w-20 object-contain">
                    <div>
                        <h1 class="text-lg sm:text-2xl font-bold text-blue-600">BERMS</h1>
                        <p class="text-xs text-gray-600 hidden sm:block">Barangay 171 Emergency Response System</p>
                    </div>
                </div>
                <a href="admin/adminlogin.php" onclick="navigateToAdminLogin(event)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 sm:py-2 sm:px-6 rounded-lg transition duration-300 flex items-center gap-1 sm:gap-2 text-sm sm:text-base">
                    <i class="fas fa-sign-in-alt"></i>
                    <span class="hidden sm:inline">Admin Login</span>
                    <span class="sm:hidden">Login</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 sm:py-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-2xl sm:text-4xl md:text-6xl font-bold mb-4 sm:mb-6 leading-tight">
                Emergency Response Made Easy
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-blue-100 mb-6 sm:mb-8 max-w-3xl mx-auto px-2">
                Report emergencies quickly and efficiently. Our system connects you with the right responders instantly.
            </p>
            <a href="#emergency-types" class="inline-block bg-white text-blue-600 font-bold py-3 px-6 sm:py-3 sm:px-8 rounded-lg hover:bg-gray-100 transition duration-300 text-base sm:text-lg">
                <i class="fas fa-phone mr-2"></i>
                Report Emergency Now
            </a>
        </div>
    </section>

    <!-- Emergency Types Section -->
    <section id="emergency-types" class="bg-gray-100 py-12 sm:py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-2xl sm:text-4xl font-bold text-center mb-8 sm:mb-16 text-gray-900 px-2">
                Types of Emergencies We Handle
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Fire -->
                <div onclick="reportEmergency('Fire')" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 h-28 sm:h-32 flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-red-600 opacity-20"></div>
                        <div class="relative z-10">
                            <svg class="w-20 h-14 sm:w-24 sm:h-16 text-white drop-shadow-lg" viewBox="0 0 100 60" fill="currentColor">
                                <!-- Fire Truck Body -->
                                <rect x="10" y="25" width="70" height="25" rx="3" fill="currentColor" opacity="0.9"/>
                                <!-- Cab -->
                                <rect x="5" y="15" width="25" height="20" rx="2" fill="currentColor"/>
                                <!-- Wheels -->
                                <circle cx="20" cy="50" r="6" fill="#333"/>
                                <circle cx="70" cy="50" r="6" fill="#333"/>
                                <!-- Ladder -->
                                <rect x="75" y="10" width="3" height="35" fill="currentColor"/>
                                <rect x="70" y="8" width="13" height="3" fill="currentColor"/>
                                <!-- Lights -->
                                <circle cx="8" cy="18" r="2" fill="#FFD700"/>
                                <circle cx="18" cy="18" r="2" fill="#FFD700"/>
                            </svg>
                            <div class="text-sm sm:text-lg font-bold text-white mt-1 tracking-wider">FIRE TRUCK</div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="text-xl sm:text-2xl font-bold text-red-600 mb-2">Fire Emergency</h4>
                        <p class="text-gray-600 text-sm sm:text-base">
                            Immediate assistance for fire-related emergencies. Our response teams are trained and equipped to handle fire situations.
                        </p>
                    </div>
                </div>

                <!-- Medical -->
                <div onclick="reportEmergency('Ambulance')" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-28 sm:h-32 flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-green-600 opacity-20"></div>
                        <div class="relative z-10">
                            <svg class="w-20 h-14 sm:w-24 sm:h-16 text-white drop-shadow-lg" viewBox="0 0 100 60" fill="currentColor">
                                <!-- Ambulance Body -->
                                <rect x="10" y="20" width="70" height="30" rx="3" fill="currentColor" opacity="0.9"/>
                                <!-- Cab -->
                                <rect x="5" y="15" width="20" height="20" rx="2" fill="currentColor"/>
                                <!-- Medical Cross -->
                                <rect x="45" y="25" width="10" height="4" fill="#FFD700"/>
                                <rect x="47" y="23" width="6" height="10" fill="#FFD700"/>
                                <!-- Windows -->
                                <rect x="12" y="22" width="8" height="6" fill="#87CEEB" opacity="0.7"/>
                                <!-- Wheels -->
                                <circle cx="25" cy="50" r="5" fill="#333"/>
                                <circle cx="65" cy="50" r="5" fill="#333"/>
                                <!-- Lights -->
                                <circle cx="8" cy="18" r="1.5" fill="#FF0000"/>
                                <circle cx="15" cy="18" r="1.5" fill="#FF0000"/>
                            </svg>
                            <div class="text-sm sm:text-lg font-bold text-white mt-1 tracking-wider">AMBULANCE</div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="text-xl sm:text-2xl font-bold text-green-600 mb-2">Medical Emergency</h4>
                        <p class="text-gray-600 text-sm sm:text-base">
                            Quick medical response for accidents, injuries, and health emergencies. Ambulances dispatched immediately.
                        </p>
                    </div>
                </div>

                <!-- Barangay Assistance -->
                <div onclick="reportEmergency('Barangay')" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-28 sm:h-32 flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-blue-600 opacity-20"></div>
                        <div class="relative z-10">
                            <svg class="w-20 h-14 sm:w-24 sm:h-16 text-white drop-shadow-lg" viewBox="0 0 100 60" fill="currentColor">
                                <!-- Building Base -->
                                <rect x="15" y="30" width="70" height="25" rx="2" fill="currentColor" opacity="0.9"/>
                                <!-- Roof -->
                                <polygon points="10,30 50,15 90,30" fill="currentColor"/>
                                <!-- Windows -->
                                <rect x="25" y="35" width="8" height="6" fill="#87CEEB" opacity="0.7"/>
                                <rect x="40" y="35" width="8" height="6" fill="#87CEEB" opacity="0.7"/>
                                <rect x="55" y="35" width="8" height="6" fill="#87CEEB" opacity="0.7"/>
                                <!-- Door -->
                                <rect x="42" y="42" width="6" height="13" fill="#8B4513"/>
                                <!-- Flag Pole -->
                                <rect x="85" y="10" width="2" height="35" fill="currentColor"/>
                                <!-- Flag -->
                                <polygon points="87,10 87,18 95,14" fill="#FFD700"/>
                                <!-- Steps -->
                                <rect x="38" y="55" width="14" height="3" fill="#666"/>
                            </svg>
                            <div class="text-sm sm:text-lg font-bold text-white mt-1 tracking-wider">BARANGAY HALL</div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="text-xl sm:text-2xl font-bold text-blue-600 mb-2">Barangay Assistance</h4>
                        <p class="text-gray-600 text-sm sm:text-base">
                            Local barangay support for community issues, security concerns, and local assistance needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 sm:py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-2xl sm:text-4xl font-bold text-center mb-8 sm:mb-16 text-gray-900 px-2">
                How It Works
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                    <div class="text-4xl sm:text-5xl text-red-500 mb-4">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">Quick Report</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Report emergencies in seconds. Select the type and provide details about the situation.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                    <div class="text-4xl sm:text-5xl text-green-500 mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">Instant Dispatch</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Your report is immediately sent to the appropriate emergency services in your area.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                    <div class="text-4xl sm:text-5xl text-blue-500 mb-4">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="text-xl sm:text-2xl font-bold mb-3 text-gray-900">Real-time Tracking</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Track response teams in real-time and stay updated on your emergency status.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 text-white py-12 sm:py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-2xl sm:text-4xl font-bold mb-4 sm:mb-6">
                In Case of Emergency
            </h3>
            <p class="text-lg sm:text-xl text-blue-100 mb-6 sm:mb-8 px-2">
                Time is critical during emergencies. Report now and help will be on the way in minutes.
            </p>
            <a href="#emergency-types" class="inline-block bg-white text-blue-600 font-bold py-3 px-6 sm:py-3 sm:px-8 rounded-lg hover:bg-gray-100 transition duration-300 text-base sm:text-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Report Emergency
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 sm:py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 mb-6 sm:mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4 text-sm sm:text-base">BERMS</h4>
                    <p class="text-xs sm:text-sm">Barangay 171 Emergency Response System</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 text-sm sm:text-base">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition text-xs sm:text-sm">About</a></li>
                        <li><a href="#" class="hover:text-white transition text-xs sm:text-sm">Contact</a></li>
                        <li><a href="#" class="text-xs sm:text-sm">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 text-sm sm:text-base">Emergency Numbers</h4>
                    <ul class="space-y-2">
                        <li class="text-xs sm:text-sm">Fire: 911</li>
                        <li class="text-xs sm:text-sm">Medical: 911</li>
                        <li class="text-xs sm:text-sm">Police: 911</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 text-sm sm:text-base">Follow Us</h4>
                    <div class="flex gap-3 sm:gap-4">
                        <a href="#" class="hover:text-white transition"><i class="fab fa-facebook text-sm sm:text-base"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-twitter text-sm sm:text-base"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-youtube text-sm sm:text-base"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-6 sm:pt-8 text-center">
                <p class="text-xs sm:text-sm">&copy; 2026 Barangay 171 Emergency Response System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function reportEmergency(type) {
            // Store the emergency type in localStorage
            localStorage.setItem('emergencyType', type);
            // Redirect directly to report form (no login required)
            window.location.href = 'pages/reportform.php';
        }

        function navigateToAdminLogin(event) {
            event.preventDefault();
            window.location.href = 'admin/adminlogin.php';
        }
    </script>
</body>
</html>
