<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - DSJBC Student Complaint Portal</title>
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
                <a href="profile.php" class="block px-4 py-2 rounded-lg bg-blue-700">
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
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-user-cog mr-3 text-blue-600"></i>Profile Settings
            </h1>

            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8 max-w-2xl mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-user-circle mr-2 text-blue-600"></i>Personal Information
                </h2>

                <div id="alert" class="mb-6"></div>

                <form id="profileForm" method="POST" action="update_profile.php">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                            <input type="text" id="course" name="course" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" id="department" name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <button type="submit" class="mt-6 w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8 max-w-2xl">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Change Password
                </h2>

                <div id="passwordAlert" class="mb-6"></div>

                <form id="passwordForm" method="POST" action="change_password.php">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <button type="submit" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Load profile data
        function loadProfile() {
            fetch('get_profile.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('first_name').value = data.user.first_name;
                        document.getElementById('last_name').value = data.user.last_name;
                        document.getElementById('email').value = data.user.email;
                        document.getElementById('phone').value = data.user.phone || '';
                        document.getElementById('course').value = data.user.course || '';
                        document.getElementById('department').value = data.user.department || '';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Update profile
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Profile updated successfully!', 'success');
                } else {
                    showAlert(data.message, 'error');
                }
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            })
            .catch(error => {
                showAlert('Error updating profile', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Change password
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                showPasswordAlert('Passwords do not match', 'error');
                return;
            }

            if (newPassword.length < 8) {
                showPasswordAlert('Password must be at least 8 characters', 'error');
                return;
            }

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
            submitBtn.disabled = true;

            fetch('change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showPasswordAlert('Password changed successfully!', 'success');
                    document.getElementById('passwordForm').reset();
                } else {
                    showPasswordAlert(data.message, 'error');
                }
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            })
            .catch(error => {
                showPasswordAlert('Error changing password', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        function showAlert(message, type) {
            const alertDiv = document.getElementById('alert');
            const bgColor = type === 'error' ? 'bg-red-100' : 'bg-green-100';
            const textColor = type === 'error' ? 'text-red-700' : 'text-green-700';
            const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';

            alertDiv.innerHTML = `
                <div class="p-4 rounded-lg ${bgColor} ${textColor} flex items-start">
                    <i class="fas ${icon} mr-3 mt-0.5 flex-shrink-0"></i>
                    <span>${message}</span>
                </div>
            `;
        }

        function showPasswordAlert(message, type) {
            const alertDiv = document.getElementById('passwordAlert');
            const bgColor = type === 'error' ? 'bg-red-100' : 'bg-green-100';
            const textColor = type === 'error' ? 'text-red-700' : 'text-green-700';
            const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';

            alertDiv.innerHTML = `
                <div class="p-4 rounded-lg ${bgColor} ${textColor} flex items-start">
                    <i class="fas ${icon} mr-3 mt-0.5 flex-shrink-0"></i>
                    <span>${message}</span>
                </div>
            `;
        }

        loadProfile();
    </script>
</body>
</html>
