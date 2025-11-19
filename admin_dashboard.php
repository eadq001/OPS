<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DSJBC Student Complaint Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'db_connection.php'; check_admin(); ?>

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:w-64 bg-red-700 text-white flex-col">
            <div class="p-6 border-b border-red-600">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-school mr-3"></i>Admin Panel
                </h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="admin_dashboard.php" class="block px-4 py-2 rounded-lg bg-red-800 hover:bg-red-900 transition">
                    <i class="fas fa-chart-line mr-3"></i>Dashboard
                </a>
                <a href="admin_complaints.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition">
                    <i class="fas fa-exclamation-circle mr-3"></i>All Complaints
                </a>
                <a href="admin_feedback.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition">
                    <i class="fas fa-comments mr-3"></i>All Feedback
                </a>
                <a href="admin_reports.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition">
                    <i class="fas fa-file-pdf mr-3"></i>Reports
                </a>
                <a href="admin_announcements.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition">
                    <i class="fas fa-bullhorn mr-3"></i>Announcements
                </a>
                <a href="admin_audit_log.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition">
                    <i class="fas fa-history mr-3"></i>Audit Log
                </a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-chart-line mr-3 text-red-700"></i>Admin Dashboard
                </h1>
                <p class="text-gray-600 mt-1">Overview of complaints and feedback submissions</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="admin_complaints.php" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Complaints</p>
                            <p class="text-3xl font-bold text-blue-600" id="total-complaints">0</p>
                        </div>
                        <i class="fas fa-exclamation-circle text-4xl text-blue-200"></i>
                    </div>
                </a>

                <a href="admin_complaints.php?status=pending" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Pending Review</p>
                            <p class="text-3xl font-bold text-yellow-600" id="pending-complaints">0</p>
                        </div>
                        <i class="fas fa-hourglass text-4xl text-yellow-200"></i>
                    </div>
                </a>

                <a href="admin_feedback.php" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Feedback</p>
                            <p class="text-3xl font-bold text-green-600" id="total-feedback">0</p>
                        </div>
                        <i class="fas fa-comment text-4xl text-green-200"></i>
                    </div>
                </a>

                <a href="admin_complaints.php?status=resolved" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Resolved</p>
                            <p class="text-3xl font-bold text-purple-600" id="resolved-count">0</p>
                        </div>
                        <i class="fas fa-check-circle text-4xl text-purple-200"></i>
                    </div>
                </a>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Complaints by Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-list mr-2 text-red-600"></i>Complaints by Status
                    </h3>
                    <div id="statusCards" class="space-y-2">
                        <p class="text-gray-500 text-center">Loading...</p>
                    </div>
                </div>

                <!-- Complaints by Type -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-tags mr-2 text-blue-600"></i>Complaints by Type
                    </h3>
                    <div id="typeCards" class="space-y-2">
                        <p class="text-gray-500 text-center">Loading...</p>
                    </div>
                </div>
            </div>

            <!-- Recent Complaints -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-list mr-2 text-red-600"></i>Recent Complaints
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Reference</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Student</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Type</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Submitted</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody id="recentComplaints" class="divide-y">
                            <tr class="text-center">
                                <td colspan="6" class="px-4 py-4 text-gray-500">Loading complaints...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="admin_complaints.php" class="text-blue-600 hover:text-blue-700 font-semibold">
                        View All Complaints <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        function loadDashboardData() {
            fetch('get_admin_dashboard.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update statistics
                        document.getElementById('total-complaints').textContent = data.stats.total_complaints;
                        document.getElementById('pending-complaints').textContent = data.stats.pending_complaints;
                        document.getElementById('total-feedback').textContent = data.stats.total_feedback;
                        document.getElementById('resolved-count').textContent = data.stats.resolved;

                        // Display status cards
                        displayStatusCards(data.chart_data.status);

                        // Display type cards
                        displayTypeCards(data.chart_data.type);

                        // Display recent complaints
                        displayRecentComplaints(data.recent_complaints);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayStatusCards(statusData) {
            const container = document.getElementById('statusCards');
            const colors = {
                'Submitted': { bg: 'bg-blue-100', text: 'text-blue-700', icon: 'fa-file' },
                'Under Review': { bg: 'bg-yellow-100', text: 'text-yellow-700', icon: 'fa-clock' },
                'In Progress': { bg: 'bg-purple-100', text: 'text-purple-700', icon: 'fa-spinner' },
                'Resolved': { bg: 'bg-green-100', text: 'text-green-700', icon: 'fa-check' }
            };

            if (statusData.data.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center">No data</p>';
                return;
            }

            let html = '';
            statusData.labels.forEach((label, index) => {
                const count = statusData.data[index];
                const color = colors[label] || { bg: 'bg-gray-100', text: 'text-gray-700', icon: 'fa-circle' };
                html += `
                    <div class="flex items-center justify-between p-3 ${color.bg} rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas ${color.icon} ${color.text} text-lg"></i>
                            <span class="${color.text} font-medium">${label}</span>
                        </div>
                        <span class="${color.text} font-bold text-xl">${count}</span>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function displayTypeCards(typeData) {
            const container = document.getElementById('typeCards');
            const colors = [
                { bg: 'bg-red-100', text: 'text-red-700' },
                { bg: 'bg-orange-100', text: 'text-orange-700' },
                { bg: 'bg-indigo-100', text: 'text-indigo-700' },
                { bg: 'bg-pink-100', text: 'text-pink-700' },
                { bg: 'bg-teal-100', text: 'text-teal-700' }
            ];

            if (typeData.data.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center">No data</p>';
                return;
            }

            let html = '';
            typeData.labels.forEach((label, index) => {
                const count = typeData.data[index];
                const color = colors[index % colors.length];
                html += `
                    <div class="flex items-center justify-between p-3 ${color.bg} rounded-lg">
                        <span class="${color.text} font-medium">${label}</span>
                        <span class="${color.text} font-bold text-xl">${count}</span>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function displayRecentComplaints(complaints) {
            const table = document.getElementById('recentComplaints');
            if (complaints.length === 0) {
                table.innerHTML = '<tr class="text-center"><td colspan="6" class="px-4 py-4 text-gray-500">No complaints yet</td></tr>';
                return;
            }

            table.innerHTML = complaints.map(complaint => {
                const statusColor = {
                    'Submitted': 'gray',
                    'Under Review': 'yellow',
                    'In Progress': 'blue',
                    'Resolved': 'green'
                }[complaint.status] || 'gray';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-mono text-gray-700">${complaint.reference_number}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">${complaint.student_name}</td>
                        <td class="px-4 py-2 text-sm"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">${complaint.complaint_type}</span></td>
                        <td class="px-4 py-2 text-sm"><span class="bg-${statusColor}-100 text-${statusColor}-700 px-2 py-1 rounded text-xs">${complaint.status}</span></td>
                        <td class="px-4 py-2 text-sm text-gray-600">${new Date(complaint.created_at).toLocaleDateString()}</td>
                        <td class="px-4 py-2 text-sm">
                            <a href="admin_view_complaint.php?id=${complaint.complaint_id}" class="text-blue-600 hover:text-blue-700 font-semibold">Review</a>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load data once on page load
        loadDashboardData();
    </script>
</body>
</html>
