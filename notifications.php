<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - DSJBC Student Complaint Portal</title>
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
                <a href="notifications.php" class="block px-4 py-2 rounded-lg bg-blue-700">
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
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bell mr-3 text-blue-600"></i>Notifications
                    </h1>
                    <p class="text-gray-600 mt-1">Stay updated on your complaints and feedback</p>
                </div>
                <button onclick="markAllAsRead()" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-check-double mr-2"></i>Mark All as Read
                </button>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <select id="filterType" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="filterNotifications()">
                    <option value="">All Notifications</option>
                    <option value="complaint_submitted">Complaint Submitted</option>
                    <option value="complaint_updated">Complaint Updated</option>
                    <option value="complaint_resolved">Complaint Resolved</option>
                    <option value="feedback_submitted">Feedback Submitted</option>
                    <option value="feedback_acknowledged">Feedback Acknowledged</option>
                </select>
            </div>

            <!-- Notifications Container -->
            <div id="notificationsContainer" class="space-y-4">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-2"></i>
                    <p class="text-gray-600">Loading notifications...</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        let allNotifications = [];

        function loadNotifications() {
            fetch('get_notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allNotifications = data.notifications;
                        displayNotifications(allNotifications);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayNotifications(notifications) {
            const container = document.getElementById('notificationsContainer');

            if (notifications.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No notifications</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = notifications.map(notif => {
                const bgColor = notif.is_read ? 'bg-gray-50' : 'bg-blue-50 border-l-4 border-blue-600';
                const iconClass = getNotificationIcon(notif.notification_type);
                
                return `
                    <div class="bg-white rounded-lg shadow-md p-6 ${bgColor} hover:shadow-lg transition cursor-pointer" onclick="markAsRead(${notif.notification_id})">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas ${iconClass} text-blue-600 text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-bold text-gray-800">${notif.title}</h3>
                                <p class="text-gray-600 text-sm mt-1">${notif.message}</p>
                                <div class="flex items-center mt-3 text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    ${formatDate(notif.created_at)}
                                    ${notif.is_read ? '<span class="ml-3 text-green-600"><i class="fas fa-check"></i> Read</span>' : '<span class="ml-3 bg-blue-600 text-white px-2 py-0.5 rounded">New</span>'}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getNotificationIcon(type) {
            const icons = {
                'complaint_submitted': 'fa-file-upload',
                'complaint_updated': 'fa-sync-alt',
                'complaint_resolved': 'fa-check-circle',
                'feedback_submitted': 'fa-comment-dots',
                'feedback_acknowledged': 'fa-handshake',
                'system': 'fa-info-circle'
            };
            return icons[type] || 'fa-bell';
        }

        function formatDate(date) {
            const d = new Date(date);
            const now = new Date();
            const diff = Math.floor((now - d) / 1000); // seconds

            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
            if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
            if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
            
            return d.toLocaleDateString();
        }

        function markAsRead(notificationId) {
            fetch('mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + notificationId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            });
        }

        function markAllAsRead() {
            fetch('mark_all_read.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            });
        }

        function filterNotifications() {
            const filter = document.getElementById('filterType').value;
            if (filter === '') {
                displayNotifications(allNotifications);
            } else {
                displayNotifications(allNotifications.filter(n => n.notification_type === filter));
            }
        }

        loadNotifications();
        setInterval(loadNotifications, 30000); // Refresh every 30 seconds
    </script>
</body>
</html>
