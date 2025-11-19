<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints - DSJBC Admin Portal</title>
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
                <a href="admin_dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 transition">
                    <i class="fas fa-chart-line mr-3"></i>Dashboard
                </a>
                <a href="admin_complaints.php" class="block px-4 py-2 rounded-lg bg-red-800">
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
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-list mr-3 text-red-700"></i>All Complaints
            </h1>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">All Status</option>
                            <option value="Submitted">Submitted</option>
                            <option value="Under Review">Under Review</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">All Types</option>
                            <option value="Academic">Academic</option>
                            <option value="Facility">Facility</option>
                            <option value="Staff">Staff</option>
                            <option value="Misconduct">Misconduct</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text" id="departmentFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Filter by department">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select id="sortFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                        <button onclick="applyFilters()" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Complaints Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Reference</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Student</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Department</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Submitted</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody id="complaintsTable" class="divide-y">
                            <tr class="text-center">
                                <td colspan="7" class="px-4 py-8 text-gray-500">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading complaints...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        let allComplaints = [];

        function loadComplaints() {
            fetch('get_all_complaints.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allComplaints = data.complaints;
                        displayComplaints(allComplaints);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayComplaints(complaints) {
            const table = document.getElementById('complaintsTable');

            if (complaints.length === 0) {
                table.innerHTML = '<tr class="text-center"><td colspan="7" class="px-4 py-8 text-gray-500">No complaints found</td></tr>';
                return;
            }

            table.innerHTML = complaints.map(complaint => {
                const statusColor = {
                    'Submitted': 'gray',
                    'Under Review': 'yellow',
                    'In Progress': 'blue',
                    'Resolved': 'green',
                    'Closed': 'purple'
                }[complaint.status] || 'gray';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono text-gray-700">${complaint.reference_number}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${complaint.student_name}</td>
                        <td class="px-4 py-3 text-sm"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">${complaint.complaint_type}</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">${complaint.course_department || 'N/A'}</td>
                        <td class="px-4 py-3 text-sm"><span class="bg-${statusColor}-100 text-${statusColor}-700 px-2 py-1 rounded text-xs">${complaint.status}</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">${new Date(complaint.created_at).toLocaleDateString()}</td>
                        <td class="px-4 py-3 text-sm">
                            <a href="admin_view_complaint.php?id=${complaint.complaint_id}" class="text-red-600 hover:text-red-700 font-semibold">Review</a>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const type = document.getElementById('typeFilter').value;
            const department = document.getElementById('departmentFilter').value.toLowerCase();
            const sort = document.getElementById('sortFilter').value;

            let filtered = allComplaints.filter(complaint => {
                if (status && complaint.status !== status) return false;
                if (type && complaint.complaint_type !== type) return false;
                if (department && !complaint.course_department.toLowerCase().includes(department)) return false;
                return true;
            });

            // Sort
            if (sort === 'oldest') {
                filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            } else {
                filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            }

            displayComplaints(filtered);
        }

        loadComplaints();
        setInterval(loadComplaints, 60000);
    </script>
</body>
</html>
