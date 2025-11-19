<?php
include 'db_connection.php';
check_login();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:w-64 bg-red-700 text-white flex-col">
            <div class="p-6 border-b border-red-600">
                <h1 class="text-2xl font-bold"><i class="fas fa-school mr-3"></i>Student Portal</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="student_dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-home mr-3"></i>Dashboard</a>
                <a href="student_feedback.php" class="block px-4 py-2 rounded-lg hover:bg-red-800"><i class="fas fa-comments mr-3"></i>Submit Feedback</a>
                <a href="student_announcements.php" class="block px-4 py-2 rounded-lg bg-red-800"><i class="fas fa-bullhorn mr-3"></i>Announcements</a>
            </nav>
            <div class="p-4 border-t border-red-600">
                <a href="logout.php" class="block px-4 py-2 rounded-lg hover:bg-red-800 text-center"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-bullhorn mr-3 text-red-700"></i>Announcements</h1>
                <p class="text-gray-600 mt-2">Stay updated with the latest announcements</p>
            </div>

            <!-- Announcements List -->
            <div class="space-y-6">
                <?php
                // Fetch all announcements ordered by newest first
                $query = "SELECT a.*, u.username FROM announcements a 
                         LEFT JOIN users u ON a.created_by = u.user_id 
                         ORDER BY a.created_at DESC";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($announcement = $result->fetch_assoc()) {
                        $priorityColor = match($announcement['priority']) {
                            'High' => 'bg-red-100 text-red-800 border-l-4 border-red-600',
                            'Medium' => 'bg-yellow-100 text-yellow-800 border-l-4 border-yellow-600',
                            'Low' => 'bg-green-100 text-green-800 border-l-4 border-green-600',
                            default => 'bg-gray-100 text-gray-800'
                        };

                        $priorityBadgeColor = match($announcement['priority']) {
                            'High' => 'bg-red-200 text-red-800',
                            'Medium' => 'bg-yellow-200 text-yellow-800',
                            'Low' => 'bg-green-200 text-green-800',
                            default => 'bg-gray-200 text-gray-800'
                        };

                        echo '
                        <div class="' . $priorityColor . ' rounded-lg p-6 shadow-md hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-gray-800">' . htmlspecialchars($announcement['title']) . '</h2>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold ' . $priorityBadgeColor . '">' . $announcement['priority'] . ' Priority</span>
                                        <span class="text-sm text-gray-600"><i class="fas fa-user mr-1"></i>Posted by ' . htmlspecialchars($announcement['username'] ?? 'Admin') . '</span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-600 whitespace-nowrap ml-4">
                                    <i class="fas fa-calendar mr-1"></i>' . date('M d, Y h:i A', strtotime($announcement['created_at'])) . '
                                </span>
                            </div>
                            <div class="mt-4">
                                <p class="text-gray-700 leading-relaxed">' . nl2br(htmlspecialchars($announcement['description'])) . '</p>
                            </div>
                        </div>
                        ';
                    }
                } else {
                    echo '
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 text-lg">No announcements yet</p>
                        <p class="text-gray-500 mt-2">Check back later for updates from administration</p>
                    </div>
                    ';
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Mobile Menu Button (optional) -->
    <style>
        @media (max-width: 768px) {
            aside {
                position: fixed;
                top: 0;
                left: -256px;
                height: 100vh;
                z-index: 50;
            }
        }
    </style>
</body>
</html>
