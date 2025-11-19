<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Details - DSJBC Student Complaint Portal</title>
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
                <a href="my_submissions.php" class="block px-4 py-2 rounded-lg hover:bg-blue-700 transition">
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
            <!-- Back Button -->
            <a href="my_submissions.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold mb-6">
                <i class="fas fa-arrow-left mr-2"></i>Back to Submissions
            </a>

            <!-- Details Container -->
            <div id="detailsContainer" class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-2"></i>
                    <p class="text-gray-600">Loading details...</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        const type = urlParams.get('type');

        function loadDetails() {
            fetch(`get_detail.php?id=${id}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayDetails(data.details, data.remarks, data.history, type);
                    } else {
                        document.getElementById('detailsContainer').innerHTML = `
                            <div class="bg-red-100 text-red-700 p-6 rounded-lg">
                                <i class="fas fa-exclamation-circle mr-2"></i>${data.error}
                            </div>
                        `;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayDetails(details, remarks, history, type) {
            const statusColor = {
                'Submitted': 'gray',
                'Under Review': 'yellow',
                'In Progress': 'blue',
                'Resolved': 'green',
                'Closed': 'purple'
            }[details.status] || 'gray';

            const html = `
                <!-- Header -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">${type === 'complaint' ? 'Complaint' : 'Feedback'} Details</h1>
                            <p class="text-gray-600 font-mono">Reference: ${details.reference_number}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="bg-${statusColor}-100 text-${statusColor}-700 px-4 py-2 rounded-lg font-semibold">${details.status}</span>
                        </div>
                    </div>
                </div>

                <!-- Main Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Student Name</p>
                            <p class="text-gray-800 font-semibold">${details.student_name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Email</p>
                            <p class="text-gray-800 font-semibold">${details.email}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Course</p>
                            <p class="text-gray-800 font-semibold">${details.course || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Submitted On</p>
                            <p class="text-gray-800 font-semibold">${new Date(details.submitted_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                        </div>
                        ${type === 'complaint' ? `
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Complaint Type</p>
                                <p class="text-gray-800 font-semibold">${details.complaint_type || details.feedback_type}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Date of Incident</p>
                                <p class="text-gray-800 font-semibold">${details.date_of_incident ? new Date(details.date_of_incident).toLocaleDateString() : 'N/A'}</p>
                            </div>
                        ` : `
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Feedback Type</p>
                                <p class="text-gray-800 font-semibold">${details.feedback_type}</p>
                            </div>
                        `}
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>Description
                    </h2>
                    <p class="text-gray-700 whitespace-pre-wrap">${details.description}</p>
                </div>

                <!-- Attached Files -->
                ${details.files && details.files.length > 0 ? `
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-paperclip mr-2 text-blue-600"></i>Attached Files
                        </h2>
                        <div class="space-y-2">
                            ${details.files.map(file => `
                                <a href="uploads/complaints/${file.stored_filename}" download="${file.original_filename}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <i class="fas fa-download text-blue-600 mr-3"></i>
                                    <span class="text-gray-700">${file.original_filename}</span>
                                    <span class="ml-auto text-xs text-gray-500">${(file.file_size / 1024).toFixed(2)} KB</span>
                                </a>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Admin Remarks -->
                ${remarks && remarks.length > 0 ? `
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-comments mr-2 text-blue-600"></i>Admin Remarks
                        </h2>
                        <div class="space-y-4">
                            ${remarks.map(remark => `
                                <div class="border-l-4 border-blue-600 bg-blue-50 p-4 rounded">
                                    <p class="text-sm text-gray-600 mb-2">By Admin • ${new Date(remark.created_at).toLocaleDateString()}</p>
                                    <p class="text-gray-800">${remark.remarks}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Status History -->
                ${history && history.length > 0 ? `
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-history mr-2 text-blue-600"></i>Status History
                        </h2>
                        <div class="space-y-3">
                            ${history.map((item, index) => `
                                <div class="flex items-start">
                                    <div class="flex flex-col items-center mr-4">
                                        <div class="w-4 h-4 bg-blue-600 rounded-full"></div>
                                        ${index < history.length - 1 ? '<div class="w-0.5 h-12 bg-gray-300"></div>' : ''}
                                    </div>
                                    <div class="pt-1">
                                        <p class="font-semibold text-gray-800">${item.new_status}</p>
                                        <p class="text-sm text-gray-600">${new Date(item.changed_at).toLocaleString()}</p>
                                        ${item.change_reason ? `<p class="text-sm text-gray-700 mt-1">${item.change_reason}</p>` : ''}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            `;

            document.getElementById('detailsContainer').innerHTML = html;
        }

        loadDetails();
    </script>
</body>
</html>
