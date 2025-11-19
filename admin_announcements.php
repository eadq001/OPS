<?php
include 'db_connection.php';
check_admin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Admin</title>
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
                <a href="admin_announcements.php" class="block px-4 py-2 rounded-lg bg-red-800"><i class="fas fa-bullhorn mr-3"></i>Announcements</a>
                <a href="admin_audit_log.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-history mr-3"></i>Audit Log</a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 text-center"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </aside>
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-bullhorn mr-3 text-red-700"></i>Announcements</h1>
                <p class="text-gray-600 mt-2">Create and manage announcements for students</p>
            </div>

            <!-- Create Announcement Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-plus mr-2 text-red-700"></i>Create New Announcement</h2>
                <form id="announcementForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" placeholder="Enter announcement title" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" rows="6" placeholder="Enter announcement details" required></textarea>
                    </div>

                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"><i class="fas fa-paper-plane mr-2"></i>Publish Announcement</button>
                </form>
            </div>

            <!-- All Announcements -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-list mr-2 text-red-700"></i>All Announcements</h2>
                
                <?php
                // Fetch all announcements
                $query = "SELECT * FROM announcements ORDER BY created_at DESC";
                $result = $conn->query($query);
                
                if ($result->num_rows > 0) {
                    echo '<div class="space-y-4">';
                    while ($announcement = $result->fetch_assoc()) {
                        $priorityColor = match($announcement['priority']) {
                            'High' => 'bg-red-100 text-red-800',
                            'Medium' => 'bg-yellow-100 text-yellow-800',
                            'Low' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                        
                        echo '
                        <div class="border border-gray-300 rounded-lg p-4 hover:shadow-md transition" id="announcement-' . $announcement['announcement_id'] . '">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800">' . htmlspecialchars($announcement['title']) . '</h3>
                                    <div class="flex gap-2 mt-2">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold ' . $priorityColor . '">' . $announcement['priority'] . ' Priority</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">' . date('M d, Y h:i A', strtotime($announcement['created_at'])) . '</p>
                            </div>
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-700">' . nl2br(htmlspecialchars($announcement['description'])) . '</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="openEditModal(' . $announcement['announcement_id'] . ', \'' . addslashes(htmlspecialchars($announcement['title'])) . '\', \'' . addslashes(htmlspecialchars($announcement['description'])) . '\', \'' . $announcement['priority'] . '\')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="fas fa-edit mr-2"></i>Edit</button>
                                <button onclick="deleteAnnouncement(' . $announcement['announcement_id'] . ')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"><i class="fas fa-trash mr-2"></i>Delete</button>
                            </div>
                        </div>
                        ';
                    }
                    echo '</div>';
                } else {
                    echo '
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 text-lg">No announcements yet. Create one above!</p>
                    </div>
                    ';
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-edit mr-2 text-red-700"></i>Edit Announcement</h2>
            
            <form id="editForm">
                <input type="hidden" id="editAnnouncementId" name="announcement_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" id="editTitle" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="editPriority" name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="editDescription" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" rows="5" required></textarea>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"><i class="fas fa-save mr-2"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('announcementForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Publishing...';
            submitBtn.disabled = true;

            fetch('api_create_announcement.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Announcement published successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create announcement'));
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                alert('Error: ' + error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        function openEditModal(id, title, description, priority) {
            document.getElementById('editAnnouncementId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            document.getElementById('editPriority').value = priority;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const announcementId = document.getElementById('editAnnouncementId').value;
            const title = document.getElementById('editTitle').value;
            const description = document.getElementById('editDescription').value;
            const priority = document.getElementById('editPriority').value;

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            fetch('api_update_announcement.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    announcement_id: parseInt(announcementId),
                    title: title,
                    description: description,
                    priority: priority
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Announcement updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update announcement'));
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                alert('Error: ' + error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        function deleteAnnouncement(id) {
            if (confirm('Are you sure you want to delete this announcement?')) {
                fetch('api_delete_announcement.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        announcement_id: id
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Announcement deleted!');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to delete'));
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    alert('Error deleting: ' + error.message);
                });
            }
        }

        // Close modal when clicking outside of it
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
