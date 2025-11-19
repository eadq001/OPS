<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Submissions - DSJBC Student Complaint Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'db_connection.php'; check_login(); ?>

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:w-64 bg-blue-600 text-white flex-col">
            <div class="p-6 border-b border-blue-500">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-school mr-3"></i>DSJBC Portal
                </h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="student_dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="submit_complaint.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-exclamation-circle mr-3"></i>Submit Complaint
                </a>
                <a href="submit_feedback.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-comment mr-3"></i>Submit Feedback
                </a>
                <a href="my_submissions.php" class="block px-4 py-2 rounded-lg bg-blue-700">
                    <i class="fas fa-list mr-3"></i>My Submissions
                </a>
                <a href="notifications.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-bell mr-3"></i>Notifications
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
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-list mr-3 text-blue-600"></i>My Submissions
                </h1>
                <p class="text-gray-600">View and track all your complaints and feedback.</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                        <select id="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            <option value="complaint">Complaints</option>
                            <option value="feedback">Feedback</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="Submitted">Submitted</option>
                            <option value="Under Review">Under Review</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select id="sortFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                        <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Reference #</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Submitted On</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Last Updated</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody id="submissionsTable" class="divide-y">
                            <tr class="text-center">
                                <td colspan="6" class="px-4 py-8 text-gray-500">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading submissions...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- No Results -->
            <div id="noResults" class="text-center py-12 hidden">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No submissions found</p>
            </div>
        </main>
    </div>

    <script>
        let allSubmissions = [];

        // Load submissions on page load
        function loadSubmissions() {
            fetch('get_submissions.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allSubmissions = data.submissions;
                        displaySubmissions(allSubmissions);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displaySubmissions(submissions) {
            const table = document.getElementById('submissionsTable');
            const noResults = document.getElementById('noResults');

            if (submissions.length === 0) {
                table.innerHTML = '';
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
                table.innerHTML = submissions.map(sub => {
                    const statusColor = {
                        'Submitted': 'gray',
                        'Under Review': 'yellow',
                        'In Progress': 'blue',
                        'Resolved': 'green',
                        'Closed': 'purple'
                    }[sub.status] || 'gray';

                    const typeIcon = sub.type === 'complaint' ? 'fa-exclamation-circle' : 'fa-comment';
                    const typeLabel = sub.type === 'complaint' ? 'Complaint' : 'Feedback';

                    return `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-700 font-mono">${sub.reference_number}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                    <i class="fas ${typeIcon} mr-1"></i>${typeLabel}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">${new Date(sub.submitted_date).toLocaleDateString()}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="bg-${statusColor}-100 text-${statusColor}-700 px-2 py-1 rounded text-xs">${sub.status}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">${new Date(sub.updated_date).toLocaleDateString()}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="view_details.php?id=${sub.id}&type=${sub.type}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        }

        function applyFilters() {
            const typeFilter = document.getElementById('typeFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;

            let filtered = allSubmissions.filter(sub => {
                if (typeFilter && sub.type !== typeFilter) return false;
                if (statusFilter && sub.status !== statusFilter) return false;
                return true;
            });

            // Sort
            if (sortFilter === 'oldest') {
                filtered.sort((a, b) => new Date(a.submitted_date) - new Date(b.submitted_date));
            } else {
                filtered.sort((a, b) => new Date(b.submitted_date) - new Date(a.submitted_date));
            }

            displaySubmissions(filtered);
        }

        loadSubmissions();
    </script>
</body>
</html>
