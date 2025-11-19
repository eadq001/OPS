<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint - DSJBC Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'db_connection.php'; check_admin(); ?>

    <div class="min-h-screen flex flex-col md:flex-row">
        <aside class="hidden md:flex md:w-64 bg-red-700 text-white flex-col">
            <div class="p-6 border-b border-red-600">
                <h1 class="text-2xl font-bold"><i class="fas fa-school mr-3"></i>Admin Panel</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="admin_dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-chart-line mr-3"></i>Dashboard</a>
                <a href="admin_complaints.php" class="block px-4 py-2 rounded-lg bg-red-800"><i class="fas fa-exclamation-circle mr-3"></i>All Complaints</a>
                <a href="admin_feedback.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-comments mr-3"></i>All Feedback</a>
                <a href="admin_reports.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-file-pdf mr-3"></i>Reports</a>
                <a href="admin_announcements.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-bullhorn mr-3"></i>Announcements</a>
                <a href="admin_audit_log.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-history mr-3"></i>Audit Log</a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 text-center"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-4 md:p-8">
            <a href="admin_complaints.php" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold mb-6">
                <i class="fas fa-arrow-left mr-2"></i>Back to Complaints
            </a>

            <div id="detailsContainer" class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-spinner fa-spin text-red-600 text-3xl mb-2"></i>
                    <p class="text-gray-600">Loading complaint details...</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        function loadDetails() {
            fetch(`admin_get_complaint.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayDetails(data.complaint, data.files, data.remarks);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayDetails(complaint, files, remarks) {
            const statusColor = {'Submitted': 'gray', 'Under Review': 'yellow', 'In Progress': 'blue', 'Resolved': 'green', 'Closed': 'purple'}[complaint.status] || 'gray';

            const html = `
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Complaint Details</h1>
                            <p class="text-gray-600 font-mono">Reference: ${complaint.reference_number}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="bg-${statusColor}-100 text-${statusColor}-700 px-4 py-2 rounded-lg font-semibold">${complaint.status}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-info-circle mr-2 text-red-600"></i>Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><p class="text-sm text-gray-600 font-medium">Student Name</p><p class="text-gray-800 font-semibold">${complaint.student_name}</p></div>
                        <div><p class="text-sm text-gray-600 font-medium">Email</p><p class="text-gray-800 font-semibold">${complaint.email}</p></div>
                        <div><p class="text-sm text-gray-600 font-medium">Complaint Type</p><p class="text-gray-800 font-semibold">${complaint.complaint_type}</p></div>
                        <div><p class="text-sm text-gray-600 font-medium">Date of Incident</p><p class="text-gray-800 font-semibold">${new Date(complaint.date_of_incident).toLocaleDateString()}</p></div>
                        <div><p class="text-sm text-gray-600 font-medium">Submitted On</p><p class="text-gray-800 font-semibold">${new Date(complaint.created_at).toLocaleDateString()}</p></div>
                        <div><p class="text-sm text-gray-600 font-medium">Department</p><p class="text-gray-800 font-semibold">${complaint.course_department || 'N/A'}</p></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-file-alt mr-2 text-red-600"></i>Description</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">${complaint.description}</p>
                </div>

                ${files && files.length > 0 ? `
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-paperclip mr-2 text-red-600"></i>Attached Files</h2>
                        <div class="space-y-2">
                            ${files.map(file => `
                                <a href="uploads/complaints/${file.stored_filename}" download="${file.original_filename}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <i class="fas fa-download text-red-600 mr-3"></i>
                                    <span class="text-gray-700">${file.original_filename}</span>
                                </a>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-edit mr-2 text-red-600"></i>Admin Response</h2>
                    <form onsubmit="submitResponse(event)">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <select id="newStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                <option value="">Select new status</option>
                                <option value="Under Review">Under Review</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Closed">Closed</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Remarks</label>
                            <textarea id="remarks" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter your remarks..." required></textarea>
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            <i class="fas fa-check mr-2"></i>Submit Response
                        </button>
                    </form>
                </div>

                ${remarks && remarks.length > 0 ? `
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-comments mr-2 text-red-600"></i>Previous Remarks</h2>
                        <div class="space-y-4">
                            ${remarks.map(remark => `
                                <div class="border-l-4 border-red-600 bg-red-50 p-4 rounded">
                                    <p class="text-sm text-gray-600 mb-2">By Admin • ${new Date(remark.created_at).toLocaleDateString()}</p>
                                    <p class="text-gray-800">${remark.remarks}</p>
                                    <p class="text-xs text-gray-500 mt-2">Status: ${remark.status_update}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            `;

            document.getElementById('detailsContainer').innerHTML = html;
        }

        function submitResponse(event) {
            event.preventDefault();
            const status = document.getElementById('newStatus').value;
            const remarks = document.getElementById('remarks').value;

            fetch('admin_update_complaint.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `complaint_id=${id}&status=${encodeURIComponent(status)}&remarks=${encodeURIComponent(remarks)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Complaint updated successfully!');
                    loadDetails();
                    document.getElementById('newStatus').value = '';
                    document.getElementById('remarks').value = '';
                }
            });
        }

        loadDetails();
    </script>
</body>
</html>
