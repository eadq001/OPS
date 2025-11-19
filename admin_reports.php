<?php
include 'db_connection.php';
check_admin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <aside class="hidden md:flex md:w-64 bg-red-700 text-white flex-col">
            <div class="p-6 border-b border-red-600">
                <h1 class="text-2xl font-bold"><i class="fas fa-school mr-3"></i>Admin Panel</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="admin_dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-chart-line mr-3"></i>Dashboard</a>
                <a href="admin_complaints.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-exclamation-circle mr-3"></i>Complaints</a>
                <a href="admin_feedback.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-comments mr-3"></i>Feedback</a>
                <a href="admin_reports.php" class="block px-4 py-2 rounded-lg bg-red-800"><i class="fas fa-file-pdf mr-3"></i>Reports</a>
                <a href="admin_announcements.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-bullhorn mr-3"></i>Announcements</a>
                <a href="admin_audit_log.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-history mr-3"></i>Audit Log</a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 text-center"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </aside>
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-file-pdf mr-3 text-red-700"></i>Reports</h1>
                <p class="text-gray-600 mt-2">Generate monthly reports and export data</p>
            </div>

            <!-- Report Options -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition" onclick="generateMonthlyReport()">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Monthly Report</h3>
                            <p class="text-sm text-gray-600">Current month summary</p>
                        </div>
                        <i class="fas fa-calendar text-4xl text-blue-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition" onclick="generateCustomReport()">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Custom Report</h3>
                            <p class="text-sm text-gray-600">Date range selection</p>
                        </div>
                        <i class="fas fa-filter text-4xl text-green-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition" onclick="generatePdfReport()">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Export PDF</h3>
                            <p class="text-sm text-gray-600">Download current report</p>
                        </div>
                        <i class="fas fa-download text-4xl text-red-200"></i>
                    </div>
                </div>
            </div>

            <!-- Report Data Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-chart-bar mr-2 text-red-700"></i>Monthly Statistics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm">Total Complaints</p>
                        <p class="text-3xl font-bold text-blue-600" id="stat-total-complaints">0</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600" id="stat-pending">0</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm">Resolved</p>
                        <p class="text-3xl font-bold text-green-600" id="stat-resolved">0</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm">Total Feedback</p>
                        <p class="text-3xl font-bold text-purple-600" id="stat-feedback">0</p>
                    </div>
                </div>
            </div>

            <!-- Complaints Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-list mr-2 text-red-700"></i>Complaints by Status</h2>
                    <div id="statusBreakdown" class="space-y-2">
                        <p class="text-gray-500">Loading...</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-tags mr-2 text-red-700"></i>Complaints by Type</h2>
                    <div id="typeBreakdown" class="space-y-2">
                        <p class="text-gray-500">Loading...</p>
                    </div>
                </div>
            </div>

            <!-- Custom Report Modal -->
            <div id="customReportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 shadow-xl">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Custom Report</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" id="startDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" id="endDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button onclick="closeCustomReportModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                        <button onclick="applyCustomReport()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Generate</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentStartDate = null;
        let currentEndDate = null;

        function loadReports() {
            const startDate = currentStartDate || new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
            const endDate = currentEndDate || new Date().toISOString().split('T')[0];

            fetch(`get_reports_data.php?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayStats(data);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayStats(data) {
            document.getElementById('stat-total-complaints').textContent = data.stats.total_complaints;
            document.getElementById('stat-pending').textContent = data.stats.pending;
            document.getElementById('stat-resolved').textContent = data.stats.resolved;
            document.getElementById('stat-feedback').textContent = data.stats.total_feedback;

            // Display status breakdown
            const statusHtml = data.breakdown.status.map(item => `
                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="font-medium">${item.status}</span>
                    <span class="font-bold text-red-600">${item.count}</span>
                </div>
            `).join('');
            document.getElementById('statusBreakdown').innerHTML = statusHtml;

            // Display type breakdown
            const typeHtml = data.breakdown.type.map(item => `
                <div class="flex justify-between p-3 bg-gray-50 rounded">
                    <span class="font-medium">${item.type}</span>
                    <span class="font-bold text-red-600">${item.count}</span>
                </div>
            `).join('');
            document.getElementById('typeBreakdown').innerHTML = typeHtml;
        }

        function generateMonthlyReport() {
            const now = new Date();
            currentStartDate = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
            currentEndDate = new Date().toISOString().split('T')[0];
            loadReports();
        }

        function generateCustomReport() {
            document.getElementById('customReportModal').classList.remove('hidden');
        }

        function closeCustomReportModal() {
            document.getElementById('customReportModal').classList.add('hidden');
        }

        function applyCustomReport() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Please select both dates');
                return;
            }

            currentStartDate = startDate;
            currentEndDate = endDate;
            loadReports();
            closeCustomReportModal();
        }

        function generatePdfReport() {
            const startDate = currentStartDate || new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
            const endDate = currentEndDate || new Date().toISOString().split('T')[0];
            window.location.href = `generate_pdf_report.php?start_date=${startDate}&end_date=${endDate}`;
        }

        // Load monthly report on page load
        generateMonthlyReport();
    </script>
</body>
</html>
