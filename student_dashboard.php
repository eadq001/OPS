<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DSJBC Student Complaint Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'db_connection.php'; check_login(); ?>

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Mobile Top Nav -->
        <nav class="md:hidden bg-blue-600 text-white p-4 flex items-center justify-between">
            <h1 class="text-xl font-bold">
                <i class="fas fa-school mr-2"></i>DSJBC Portal
            </h1>
            <button id="mobileMenuBtn" class="text-white text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden bg-blue-600 text-white">
            <nav class="flex flex-col space-y-0">
                <a href="student_dashboard.php" class="block px-4 py-3 border-b border-blue-500 bg-blue-700 hover:bg-blue-800 transition">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="submit_complaint.php" class="block px-4 py-3 border-b border-blue-500 hover:bg-blue-700 transition">
                    <i class="fas fa-exclamation-circle mr-3"></i>Submit Complaint
                </a>
                <a href="submit_feedback.php" class="block px-4 py-3 border-b border-blue-500 hover:bg-blue-700 transition">
                    <i class="fas fa-comment mr-3"></i>Submit Feedback
                </a>
                <a href="my_submissions.php" class="block px-4 py-3 border-b border-blue-500 hover:bg-blue-700 transition">
                    <i class="fas fa-list mr-3"></i>My Submissions
                </a>
                <a href="notifications.php" class="block px-4 py-3 border-b border-blue-500 hover:bg-blue-700 transition">
                    <i class="fas fa-bell mr-3"></i>Notifications
                    <span id="notif-badge" class="ml-2 bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
                <a href="profile.php" class="block px-4 py-3 border-b border-blue-500 hover:bg-blue-700 transition">
                    <i class="fas fa-user mr-3"></i>Profile Settings
                </a>
                <a href="logout.php" class="block px-4 py-3 hover:bg-blue-700 transition text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Sidebar -->
        <aside class="hidden md:flex md:w-64 bg-blue-600 text-white flex-col">
            <div class="p-6 border-b border-blue-500">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-school mr-3"></i>DSJBC Portal
                </h1>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="student_dashboard.php" class="block px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 transition">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="submit_complaint.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-exclamation-circle mr-3"></i>Submit Complaint
                </a>
                <a href="submit_feedback.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-comment mr-3"></i>Submit Feedback
                </a>
                <a href="my_submissions.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-list mr-3"></i>My Submissions
                </a>
                <a href="notifications.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition relative">
                    <i class="fas fa-bell mr-3"></i>Notifications
                    <span id="notif-badge" class="absolute top-2 right-2 bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
                <a href="profile.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-user mr-3"></i>Profile Settings
                </a>
            </nav>

            <div class="p-4 border-t border-blue-500">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8">

            <!-- Welcome Section -->
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Welcome, <?php echo $_SESSION['first_name']; ?>! 👋
                </h2>
                <p class="text-gray-600">Manage your complaints and feedback efficiently through our portal.</p>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Submit Complaint Card -->
                <a href="submit_complaint.php" class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 block">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 ml-4">Submit Complaint</h3>
                    </div>
                    <p class="text-gray-600 text-sm">Report academic, facility, or staff issues</p>
                </a>

                <!-- Submit Feedback Card -->
                <a href="submit_feedback.php" class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 block">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-comment text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 ml-4">Submit Feedback</h3>
                    </div>
                    <p class="text-gray-600 text-sm">Share suggestions, compliments, or general comments</p>
                </a>

                <!-- My Submissions Card -->
                <a href="my_submissions.php" class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 block">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 ml-4">My Submissions</h3>
                    </div>
                    <p class="text-gray-600 text-sm">View all your complaints and feedback</p>
                </a>
            </div>

            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-blue-600" id="total-submissions">0</div>
                    <p class="text-gray-600 text-sm">Total Submissions</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-yellow-600" id="pending-count">0</div>
                    <p class="text-gray-600 text-sm">Pending</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-orange-600" id="review-count">0</div>
                    <p class="text-gray-600 text-sm">Under Review</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-green-600" id="resolved-count">0</div>
                    <p class="text-gray-600 text-sm">Resolved</p>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-bullhorn mr-2 text-blue-600"></i>Recent Announcements
                </h3>
                <div id="announcements-container" class="space-y-3">
                    <p class="text-gray-500 italic">Loading announcements...</p>
                </div>
            </div>

            <!-- Recent Submissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-history mr-2 text-blue-600"></i>Recent Submissions
                </h3>
                <div id="recent-submissions-container" class="overflow-x-auto">
                    <p class="text-gray-500 italic">Loading submissions...</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when a link is clicked
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // Load dashboard data
        function loadDashboardData() {
            fetch('get_dashboard_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-submissions').textContent = data.total;
                        document.getElementById('pending-count').textContent = data.pending;
                        document.getElementById('review-count').textContent = data.under_review;
                        document.getElementById('resolved-count').textContent = data.resolved;
                        document.getElementById('notif-badge').textContent = data.unread_notifications;
                        
                        if (data.unread_notifications > 0) {
                            document.getElementById('notif-badge').classList.remove('hidden');
                        }

                        // Load announcements
                        const announcementsContainer = document.getElementById('announcements-container');
                        if (data.announcements.length > 0) {
                            announcementsContainer.innerHTML = data.announcements.map(ann => `
                                <div class="border-l-4 border-blue-600 bg-blue-50 p-4 rounded">
                                    <h4 class="font-bold text-gray-800">${ann.title}</h4>
                                    <p class="text-gray-600 text-sm mt-1">${ann.description.substring(0, 150)}...</p>
                                    <p class="text-xs text-gray-500 mt-2">${new Date(ann.created_at).toLocaleDateString()}</p>
                                </div>
                            `).join('');
                        } else {
                            announcementsContainer.innerHTML = '<p class="text-gray-500 italic">No announcements yet</p>';
                        }

                        // Load recent submissions
                        const recentContainer = document.getElementById('recent-submissions-container');
                        if (data.recent_submissions.length > 0) {
                            const table = `
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold">Reference</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold">Type</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold">Date</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.recent_submissions.map(sub => {
                                            const statusColor = sub.status === 'Resolved' ? 'green' : sub.status === 'Under Review' ? 'yellow' : 'gray';
                                            return `
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="px-4 py-2 text-sm">${sub.reference_number}</td>
                                                    <td class="px-4 py-2 text-sm"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">${sub.type}</span></td>
                                                    <td class="px-4 py-2 text-sm">${new Date(sub.date).toLocaleDateString()}</td>
                                                    <td class="px-4 py-2 text-sm"><span class="bg-${statusColor}-100 text-${statusColor}-700 px-2 py-1 rounded text-xs">${sub.status}</span></td>
                                                    <td class="px-4 py-2 text-sm"><a href="view_details.php?id=${sub.id}&type=${sub.type_code}" class="text-blue-600 hover:underline">View</a></td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                            `;
                            recentContainer.innerHTML = table;
                        } else {
                            recentContainer.innerHTML = '<p class="text-gray-500 italic">No submissions yet</p>';
                        }
                    }
                })
                .catch(error => console.error('Error loading dashboard:', error));
        }

        loadDashboardData();
        setInterval(loadDashboardData, 30000); // Refresh every 30 seconds
    </script>
</body>
</html>
