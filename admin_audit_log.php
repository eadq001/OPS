<?php
include 'db_connection.php';
check_admin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Admin</title>
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
                <a href="admin_reports.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-file-pdf mr-3"></i>Reports</a>
                <a href="admin_announcements.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-bullhorn mr-3"></i>Announcements</a>
                <a href="admin_audit_log.php" class="block px-4 py-2 rounded-lg bg-red-800"><i class="fas fa-history mr-3"></i>Audit Log</a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 text-center"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </aside>
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-history mr-3 text-red-700"></i>Audit Log</h1>
                <p class="text-gray-600 mt-2">Track all system activities and changes</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Action</label>
                        <select id="filterAction" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                            <option value="">All Actions</option>
                            <option value="CREATE">Create</option>
                            <option value="UPDATE">Update</option>
                            <option value="DELETE">Delete</option>
                            <option value="LOGIN">Login</option>
                            <option value="LOGOUT">Logout</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Table</label>
                        <select id="filterTable" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                            <option value="">All Tables</option>
                            <option value="complaints">Complaints</option>
                            <option value="feedback">Feedback</option>
                            <option value="users">Users</option>
                            <option value="announcements">Announcements</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="searchInput" placeholder="Search user or ID..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>
                </div>
                <button onclick="applyFilters()" class="mt-4 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"><i class="fas fa-filter mr-2"></i>Apply Filters</button>
            </div>

            <!-- Audit Log Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Timestamp</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Table</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Record ID</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">IP Address</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Details</th>
                            </tr>
                        </thead>
                        <tbody id="auditTable" class="divide-y">
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">Loading audit logs...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center gap-2" id="pagination">
            </div>
        </main>
    </div>

    <script>
        let currentPage = 1;
        let allLogs = [];

        function loadAuditLogs() {
            fetch('get_audit_logs.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allLogs = data.logs;
                        displayLogs(allLogs);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayLogs(logs) {
            const table = document.getElementById('auditTable');
            
            if (logs.length === 0) {
                table.innerHTML = '<tr><td colspan="7" class="px-4 py-4 text-center text-gray-500">No audit logs found</td></tr>';
                return;
            }

            const itemsPerPage = 10;
            const totalPages = Math.ceil(logs.length / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedLogs = logs.slice(start, end);

            table.innerHTML = paginatedLogs.map(log => {
                const actionColor = {
                    'CREATE': 'bg-green-100 text-green-800',
                    'UPDATE': 'bg-blue-100 text-blue-800',
                    'DELETE': 'bg-red-100 text-red-800',
                    'LOGIN': 'bg-purple-100 text-purple-800',
                    'LOGOUT': 'bg-gray-100 text-gray-800'
                }[log.action] || 'bg-gray-100 text-gray-800';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700">${new Date(log.created_at).toLocaleString()}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${log.username || 'System'}</td>
                        <td class="px-4 py-3 text-sm"><span class="px-2 py-1 rounded text-xs font-semibold ${actionColor}">${log.action}</span></td>
                        <td class="px-4 py-3 text-sm text-gray-700">${log.table_name}</td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-700">${log.record_id}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">${log.ip_address}</td>
                        <td class="px-4 py-3 text-sm">
                            <button onclick="viewDetails('${log.log_id}')" class="text-blue-600 hover:text-blue-700 font-semibold">View</button>
                        </td>
                    </tr>
                `;
            }).join('');

            // Display pagination
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = i === currentPage 
                    ? 'px-3 py-1 bg-red-600 text-white rounded' 
                    : 'px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400';
                btn.textContent = i;
                btn.onclick = () => {
                    currentPage = i;
                    displayLogs(allLogs);
                };
                pagination.appendChild(btn);
            }
        }

        function applyFilters() {
            const action = document.getElementById('filterAction').value;
            const table = document.getElementById('filterTable').value;
            const search = document.getElementById('searchInput').value.toLowerCase();

            const filtered = allLogs.filter(log => {
                const actionMatch = !action || log.action === action;
                const tableMatch = !table || log.table_name === table;
                const searchMatch = !search || 
                    log.username?.toLowerCase().includes(search) ||
                    log.record_id?.toString().includes(search) ||
                    log.ip_address?.includes(search);
                
                return actionMatch && tableMatch && searchMatch;
            });

            currentPage = 1;
            displayLogs(filtered);
        }

        function viewDetails(logId) {
            const log = allLogs.find(l => l.log_id == logId);
            if (log) {
                alert(`Details for Log ID ${logId}:\n\n` +
                    `Action: ${log.action}\n` +
                    `Table: ${log.table_name}\n` +
                    `Record ID: ${log.record_id}\n` +
                    `User: ${log.username}\n` +
                    `IP: ${log.ip_address}\n` +
                    `Time: ${new Date(log.created_at).toLocaleString()}\n` +
                    `Old Values: ${log.old_values || 'N/A'}\n` +
                    `New Values: ${log.new_values || 'N/A'}`);
            }
        }

        // Load logs on page load
        loadAuditLogs();
    </script>
</body>
</html>
