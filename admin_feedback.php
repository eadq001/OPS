<?php
include 'db_connection.php';
check_admin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Feedback - Admin</title>
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
                <a href="admin_feedback.php" class="block px-4 py-2 rounded-lg bg-red-800"><i class="fas fa-comments mr-3"></i>Feedback</a>
                <a href="admin_reports.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-file-pdf mr-3"></i>Reports</a>
                <a href="admin_announcements.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-bullhorn mr-3"></i>Announcements</a>
                <a href="admin_audit_log.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-history mr-3"></i>Audit Log</a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 text-center"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </aside>
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-comments mr-3 text-red-700"></i>All Feedback</h1>
                <p class="text-gray-600 mt-2">View and manage feedback from students</p>
            </div>

            <!-- Status Filter -->
            <div class="mb-6 flex gap-2">
                <button onclick="filterByStatus('')" class="px-4 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-50">All</button>
                <button onclick="filterByStatus('Submitted')" class="px-4 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-50">Submitted</button>
                <button onclick="filterByStatus('Acknowledged')" class="px-4 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-50">Acknowledged</button>
                <button onclick="filterByStatus('Under Review')" class="px-4 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-50">Under Review</button>
                <button onclick="filterByStatus('Closed')" class="px-4 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-50">Closed</button>
            </div>

            <?php
            // Fetch all feedbacks
            $query = "SELECT f.*, u.username FROM feedback f 
                      LEFT JOIN users u ON f.user_id = u.user_id 
                      ORDER BY f.created_at DESC";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                echo '<div class="space-y-4">';
                while ($feedback = $result->fetch_assoc()) {
                    $typeColor = match($feedback['feedback_type']) {
                        'Suggestion' => 'bg-blue-100 text-blue-800',
                        'Compliment' => 'bg-green-100 text-green-800',
                        'General Comment' => 'bg-purple-100 text-purple-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    
                    $statusColor = match($feedback['status']) {
                        'Submitted' => 'bg-yellow-100 text-yellow-800',
                        'Acknowledged' => 'bg-blue-100 text-blue-800',
                        'Under Review' => 'bg-orange-100 text-orange-800',
                        'Closed' => 'bg-green-100 text-green-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    
                    echo '
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex gap-2 mb-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold ' . $typeColor . '">' . $feedback['feedback_type'] . '</span>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold ' . $statusColor . '">' . $feedback['status'] . '</span>
                                </div>
                                <p class="text-gray-600"><strong>Reference #:</strong> ' . $feedback['reference_number'] . '</p>
                                <p class="text-gray-600"><strong>From:</strong> ' . ($feedback['username'] ? $feedback['username'] : 'Anonymous') . '</p>
                                <p class="text-gray-600"><strong>Department:</strong> ' . $feedback['department_office'] . '</p>
                            </div>
                            <p class="text-sm text-gray-500">' . date('M d, Y h:i A', strtotime($feedback['created_at'])) . '</p>
                        </div>
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">' . nl2br(htmlspecialchars($feedback['description'])) . '</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" id="status_' . $feedback['feedback_id'] . '">
                                    <option value="Submitted" ' . ($feedback['status'] === 'Submitted' ? 'selected' : '') . '>Submitted</option>
                                    <option value="Acknowledged" ' . ($feedback['status'] === 'Acknowledged' ? 'selected' : '') . '>Acknowledged</option>
                                    <option value="Under Review" ' . ($feedback['status'] === 'Under Review' ? 'selected' : '') . '>Under Review</option>
                                    <option value="Closed" ' . ($feedback['status'] === 'Closed' ? 'selected' : '') . '>Closed</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button onclick="quickUpdateStatus(' . $feedback['feedback_id'] . ')" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"><i class="fas fa-save mr-2"></i>Save</button>
                            </div>
                        </div>
                    </div>
                    ';
                }
                echo '</div>';
            } else {
                echo '
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">No feedback received yet</p>
                </div>
                ';
            }
            ?>
        </main>
    </div>

    <!-- View Feedback Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Feedback Details</h2>
                <button onclick="closeModal('viewModal')" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times text-2xl"></i></button>
            </div>
            <div id="modalContent"></div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Update Status</h2>
                <button onclick="closeModal('statusModal')" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times text-2xl"></i></button>
            </div>
            <form id="statusForm">
                <input type="hidden" id="feedbackId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="newStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" required>
                        <option value="">Select Status</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Acknowledged">Acknowledged</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Update Status</button>
            </form>
        </div>
    </div>

    <script>
        function filterByStatus(status) {
            // Reload page with status filter
            window.location.href = '?status=' + status;
        }

        function quickUpdateStatus(feedbackId) {
            const newStatus = document.getElementById('status_' + feedbackId).value;
            
            fetch('api_update_feedback_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    feedback_id: feedbackId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error updating status: ' + error);
            });
        }

        function viewFeedback(feedbackId) {
            // Fetch feedback details and display
            document.getElementById('viewModal').classList.remove('hidden');
            // In a real app, you'd fetch the details via AJAX
        }

        function updateStatus(feedbackId) {
            document.getElementById('feedbackId').value = feedbackId;
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const feedbackId = document.getElementById('feedbackId').value;
            const newStatus = document.getElementById('newStatus').value;
            
            fetch('api_update_feedback_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    feedback_id: feedbackId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating status');
                }
            });
        });
    </script>
</body>
</html>
