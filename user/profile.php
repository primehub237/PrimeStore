<?php 
include '../includes/header.php'; 

?>   
    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #F4E4B3;
            --gold-dark: #B8941F;
            --yellow-primary: #facc15;
            --yellow-secondary: #eab308;
            --yellow-tertiary: #ca8a04;
        }

        body {
            background: linear-gradient(135deg, #fafafa 0%, #f0f0f0 100%);
            min-height: 100vh;
            padding-bottom: 80px;
        }

        .tab-active {
            color: var(--yellow-secondary);
            border-bottom: 3px solid var(--yellow-secondary);
        }

        .profile-card {
            background: white;
            border: 1px solid rgba(212, 175, 55, 0.15);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.05) 0%, rgba(234, 179, 8, 0.08) 100%);
            border: 1px solid rgba(212, 175, 55, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(250, 204, 21, 0.15);
            border-color: var(--yellow-secondary);
        }

        .order-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: var(--yellow-secondary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .mobile-tab-bar {
            background: white;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.05);
        }

        .mobile-tab {
            transition: all 0.3s ease;
        }

        .mobile-tab.active {
            color: var(--yellow-secondary);
        }

        .mobile-tab.active svg {
            transform: scale(1.15);
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-avatar {
            background: linear-gradient(135deg, var(--yellow-primary), var(--yellow-secondary));
            box-shadow: 0 8px 20px rgba(250, 204, 21, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--yellow-primary), var(--yellow-secondary));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(250, 204, 21, 0.3);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .info-row {
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .section-divider {
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
            height: 1px;
            margin: 2rem 0;
        }
    </style>
</head>
<body class="text-gray-900">


    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        
        <!-- Profile Header Section -->
        <div class="profile-card rounded-2xl p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Avatar -->
                <div class="profile-avatar w-28 h-28 md:w-36 md:h-36 rounded-full flex items-center justify-center text-4xl md:text-5xl font-bold text-white">
                    JD
                </div>
                
                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900">John Doe</h1>
                    <p class="text-gray-600 mb-1 text-lg">Premium Member</p>
                    <p class="text-gray-500 text-sm">Member since January 2024</p>
                    
                    <!-- Desktop Tabs -->
                    <div class="hidden md:flex gap-8 mt-8 border-b border-gray-200">
                        <button onclick="switchTab('profile')" class="tab-btn tab-active pb-3 font-semibold transition-all text-lg" data-tab="profile">Profile</button>
                        <button onclick="switchTab('history')" class="tab-btn pb-3 font-semibold text-gray-500 transition-all text-lg" data-tab="history">Order History</button>
                        <button onclick="switchTab('settings')" class="tab-btn pb-3 font-semibold text-gray-500 transition-all text-lg" data-tab="settings">Settings</button>
                    </div>
                </div>
                
                <!-- Edit Button -->
                <button class="btn-primary px-6 py-3 rounded-xl font-semibold text-gray-900 hover:shadow-lg">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </span>
                </button>
            </div>
        </div>

        <!-- Profile Tab -->
        <div id="profile-tab" class="tab-content active">
            <!-- Stats Section -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2 text-yellow-600">42</div>
                    <div class="text-gray-600 text-sm font-medium">Total Orders</div>
                </div>
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2 text-yellow-600">$5,230</div>
                    <div class="text-gray-600 text-sm font-medium">Total Spent</div>
                </div>
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2 text-yellow-600">12</div>
                    <div class="text-gray-600 text-sm font-medium">Wishlist Items</div>
                </div>
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2 text-yellow-600">850</div>
                    <div class="text-gray-600 text-sm font-medium">Reward Points</div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="profile-card rounded-2xl p-6 md:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Personal Information</h2>
                    <div class="space-y-4">
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Full Name</label>
                            <p class="text-gray-900 font-semibold text-lg">John Doe</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Email Address</label>
                            <p class="text-gray-900 font-semibold">john.doe@email.com</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Phone Number</label>
                            <p class="text-gray-900 font-semibold">+1 (234) 567-8900</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Date of Birth</label>
                            <p class="text-gray-900 font-semibold">January 15, 1990</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Gender</label>
                            <p class="text-gray-900 font-semibold">Male</p>
                        </div>
                    </div>
                </div>

                <div class="profile-card rounded-2xl p-6 md:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Shipping Address</h2>
                    <div class="space-y-4">
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Street Address</label>
                            <p class="text-gray-900 font-semibold">123 Main Street, Apt 4B</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">City</label>
                            <p class="text-gray-900 font-semibold">New York</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">State / Province</label>
                            <p class="text-gray-900 font-semibold">New York</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Postal Code</label>
                            <p class="text-gray-900 font-semibold">10001</p>
                        </div>
                        <div class="info-row py-4">
                            <label class="text-gray-500 text-sm font-medium block mb-1">Country</label>
                            <p class="text-gray-900 font-semibold">United States</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Activity -->
            <div class="profile-card rounded-2xl p-6 md:p-8 mt-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Account Activity</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="text-gray-500 text-sm font-medium mb-1">Last Order</p>
                        <p class="text-gray-900 font-bold text-lg">October 18, 2024</p>
                    </div>
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="text-gray-500 text-sm font-medium mb-1">Last Login</p>
                        <p class="text-gray-900 font-bold text-lg">October 22, 2024</p>
                    </div>
                    <div class="border-l-4 border-purple-500 pl-4">
                        <p class="text-gray-500 text-sm font-medium mb-1">Account Status</p>
                        <p class="text-gray-900 font-bold text-lg">Active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History Tab -->
        <div id="history-tab" class="tab-content">
            <div class="profile-card rounded-2xl p-6 md:p-8">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Order History</h2>
                <div class="space-y-4">
                    <!-- Order 1 -->
                    <div class="order-card rounded-xl p-5 cursor-pointer" onclick="showOrderDetails(1)">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-bold text-gray-900 text-lg">Order #ORD-2024-10-001</span>
                                    <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">Delivered</span>
                                </div>
                                <p class="text-gray-600 mb-1 font-medium">Apple MacBook Pro 16" + 2 more items</p>
                                <p class="text-gray-500 text-sm">Ordered on October 15, 2024</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="font-bold text-2xl text-yellow-600">$2,899.97</p>
                                </div>
                                <button class="px-5 py-2 bg-gray-100 hover:bg-yellow-100 rounded-lg text-sm font-semibold transition-all border border-gray-200">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 2 -->
                    <div class="order-card rounded-xl p-5 cursor-pointer" onclick="showOrderDetails(2)">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-bold text-gray-900 text-lg">Order #ORD-2024-10-002</span>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">Shipped</span>
                                </div>
                                <p class="text-gray-600 mb-1 font-medium">Sony WH-1000XM5 Headphones</p>
                                <p class="text-gray-500 text-sm">Ordered on October 18, 2024</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="font-bold text-2xl text-yellow-600">$399.99</p>
                                </div>
                                <button class="px-5 py-2 bg-gray-100 hover:bg-yellow-100 rounded-lg text-sm font-semibold transition-all border border-gray-200">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 3 -->
                    <div class="order-card rounded-xl p-5 cursor-pointer" onclick="showOrderDetails(3)">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-bold text-gray-900 text-lg">Order #ORD-2024-09-045</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-semibold">Processing</span>
                                </div>
                                <p class="text-gray-600 mb-1 font-medium">Samsung 4K Monitor 32"</p>
                                <p class="text-gray-500 text-sm">Ordered on September 28, 2024</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="font-bold text-2xl text-yellow-600">$549.99</p>
                                </div>
                                <button class="px-5 py-2 bg-gray-100 hover:bg-yellow-100 rounded-lg text-sm font-semibold transition-all border border-gray-200">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tab -->
        <div id="settings-tab" class="tab-content">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Account Settings -->
                <div class="profile-card rounded-2xl p-6 md:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Account Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div>
                                <p class="font-semibold text-gray-900">Email Notifications</p>
                                <p class="text-sm text-gray-500">Receive order updates via email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div>
                                <p class="font-semibold text-gray-900">SMS Notifications</p>
                                <p class="text-sm text-gray-500">Get delivery alerts via SMS</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div>
                                <p class="font-semibold text-gray-900">Marketing Emails</p>
                                <p class="text-sm text-gray-500">Receive promotional offers</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="font-semibold text-gray-900">Two-Factor Authentication</p>
                                <p class="text-sm text-gray-500">Add extra security to your account</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="profile-card rounded-2xl p-6 md:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Security</h2>
                    <div class="space-y-4">
                        <button class="w-full text-left p-4 bg-gray-50 hover:bg-yellow-50 rounded-xl transition-all border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Change Password</p>
                                    <p class="text-sm text-gray-500">Update your password regularly</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </button>
                        <button class="w-full text-left p-4 bg-gray-50 hover:bg-yellow-50 rounded-xl transition-all border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Login History</p>
                                    <p class="text-sm text-gray-500">View recent account activity</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </button>
                        <button class="w-full text-left p-4 bg-gray-50 hover:bg-yellow-50 rounded-xl transition-all border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Connected Devices</p>
                                    <p class="text-sm text-gray-500">Manage devices with access</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                    <div class="section-divider"></div>
                    <button class="w-full p-4 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-xl transition-all border border-red-200">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Tab Bar -->
    <div class="mobile-tab-bar fixed bottom-0 left-0 right-0 md:hidden z-50">
        <div class="flex justify-around items-center py-3">
            <button onclick="switchTab('profile')" class="mobile-tab active flex flex-col items-center gap-1" data-mobile-tab="profile">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </button>
            <button onclick="switchTab('history')" class="mobile-tab flex flex-col items-center gap-1 text-gray-500" data-mobile-tab="history">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-medium">History</span>
            </button>
            <button onclick="switchTab('settings')" class="mobile-tab flex flex-col items-center gap-1 text-gray-500" data-mobile-tab="settings">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-xs font-medium">Settings</span>
            </button>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900" id="modalOrderNumber">Order Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Order Status -->
                <div class="mb-6 p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order Status</p>
                            <p class="font-bold text-xl text-gray-900" id="modalStatus">Delivered</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Order Date</p>
                            <p class="font-semibold text-gray-900" id="modalDate">October 15, 2024</p>
                        </div>
                    </div>
                </div>

                <!-- Store Information -->
                <div class="mb-6">
                    <h4 class="font-bold text-lg text-gray-900 mb-3">Store Information</h4>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900" id="modalStore">Tech Haven Electronics</p>
                            <p class="text-sm text-gray-600">Official Apple Reseller</p>
                            <p class="text-sm text-gray-500 mt-1">Contact: +1 (555) 123-4567</p>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="mb-6">
                    <h4 class="font-bold text-lg text-gray-900 mb-3">Products</h4>
                    <div class="space-y-3" id="modalProducts">
                        <!-- Products will be inserted here dynamically -->
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span id="modalSubtotal">$2,799.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span id="modalShipping">$50.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax</span>
                            <span id="modalTax">$50.97</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-900 pt-3 border-t border-gray-300">
                        <span>Total</span>
                        <span class="text-yellow-600" id="modalTotal">$2,899.97</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex gap-3">
                    <button class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-xl transition-all">
                        Download Invoice
                    </button>
                    <button class="flex-1 px-4 py-3 btn-primary text-gray-900 font-semibold rounded-xl">
                        Track Order
                    </button>
                </div>
            </div>
        </div>
    </div>



    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Update desktop tab styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('text-gray-500');
            });
            
            const activeDesktopTab = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
            if (activeDesktopTab) {
                activeDesktopTab.classList.add('tab-active');
                activeDesktopTab.classList.remove('text-gray-500');
            }
            
            // Update mobile tab styles
            document.querySelectorAll('.mobile-tab').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-gray-500');
            });
            
            const activeMobileTab = document.querySelector(`.mobile-tab[data-mobile-tab="${tabName}"]`);
            if (activeMobileTab) {
                activeMobileTab.classList.add('active');
                activeMobileTab.classList.remove('text-gray-500');
            }
            
            // Scroll to top on mobile
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Order data
        const orders = {
            1: {
                number: 'ORD-2024-10-001',
                status: 'Delivered',
                statusClass: 'bg-green-100 text-green-700',
                date: 'October 15, 2024',
                store: 'Tech Haven Electronics',
                storeDesc: 'Official Apple Reseller',
                products: [
                    {
                        name: 'Apple MacBook Pro 16"',
                        image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400',
                        price: '$2,499.00',
                        quantity: 1
                    },
                    {
                        name: 'USB-C Charging Cable',
                        image: 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=400',
                        price: '$29.00',
                        quantity: 2
                    },
                    {
                        name: 'Laptop Sleeve Case',
                        image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400',
                        price: '$49.99',
                        quantity: 1
                    }
                ],
                subtotal: '$2,799.00',
                shipping: '$50.00',
                tax: '$50.97',
                total: '$2,899.97'
            },
            2: {
                number: 'ORD-2024-10-002',
                status: 'Shipped',
                statusClass: 'bg-blue-100 text-blue-700',
                date: 'October 18, 2024',
                store: 'Audio Paradise Store',
                storeDesc: 'Premium Audio Equipment',
                products: [
                    {
                        name: 'Sony WH-1000XM5 Headphones',
                        image: 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=400',
                        price: '$399.99',
                        quantity: 1
                    }
                ],
                subtotal: '$399.99',
                shipping: '$0.00',
                tax: '$0.00',
                total: '$399.99'
            },
            3: {
                number: 'ORD-2024-09-045',
                status: 'Processing',
                statusClass: 'bg-yellow-100 text-yellow-700',
                date: 'September 28, 2024',
                store: 'Display World',
                storeDesc: 'Professional Monitors & Displays',
                products: [
                    {
                        name: 'Samsung 4K Monitor 32"',
                        image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400',
                        price: '$549.99',
                        quantity: 1
                    }
                ],
                subtotal: '$549.99',
                shipping: '$0.00',
                tax: '$0.00',
                total: '$549.99'
            }
        };

        function showOrderDetails(orderId) {
            const order = orders[orderId];
            const modal = document.getElementById('orderModal');
            
            // Update modal content
            document.getElementById('modalOrderNumber').textContent = `Order ${order.number}`;
            document.getElementById('modalStatus').textContent = order.status;
            document.getElementById('modalDate').textContent = order.date;
            document.getElementById('modalStore').textContent = order.store;
            document.getElementById('modalSubtotal').textContent = order.subtotal;
            document.getElementById('modalShipping').textContent = order.shipping;
            document.getElementById('modalTax').textContent = order.tax;
            document.getElementById('modalTotal').textContent = order.total;
            
            // Update products
            const productsContainer = document.getElementById('modalProducts');
            productsContainer.innerHTML = order.products.map(product => `
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                    <img src="${product.image}" alt="${product.name}" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">${product.name}</p>
                        <p class="text-sm text-gray-600">Quantity: ${product.quantity}</p>
                    </div>
                    <p class="font-bold text-yellow-600">${product.price}</p>
                </div>
            `).join('');
            
            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('orderModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('orderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
    <!-- Footer Placeholder -->
    <?php include '../includes/footer.php'; ?>