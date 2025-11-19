<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - DSJBC Student Complaint Portal</title>
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
                <a href="submit_feedback.php" class="block px-4 py-2 rounded-lg bg-blue-700">
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
            <a href="student_dashboard.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold mb-6">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 max-w-2xl">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-comment-dots mr-3 text-blue-600"></i>Submit Feedback
                </h1>
                <p class="text-gray-600 mb-6">Share your suggestions, compliments, or general comments with us.</p>

                <!-- Alert Messages -->
                <div id="alert" class="mb-6"></div>

                <form id="feedbackForm" method="POST" action="process_feedback.php">
                    <!-- Feedback Type -->
                    <div class="mb-6">
                        <label for="feedback_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Feedback Type
                        </label>
                        <select id="feedback_type" name="feedback_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select feedback type...</option>
                            <option value="Suggestion">Suggestion for Improvement</option>
                            <option value="Compliment">Compliment</option>
                            <option value="General Comment">General Comment</option>
                        </select>
                    </div>

                    <!-- Department / Office -->
                    <div class="mb-6">
                        <label for="department_office" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Department / Office Concerned
                        </label>
                        <select id="department_office" name="department_office" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select department...</option>
                            <option value="Academic Affairs">Academic Affairs</option>
                            <option value="Registrar">Registrar's Office</option>
                            <option value="Student Services">Student Services</option>
                            <option value="Facilities">Facilities Management</option>
                            <option value="Library">Library Services</option>
                            <option value="Cafeteria">Cafeteria</option>
                            <option value="IT Services">IT Services</option>
                            <option value="General Administration">General Administration</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Feedback Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Your Feedback
                        </label>
                        <textarea id="description" name="description" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Please share your feedback in detail..." required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 20 characters required</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Feedback
                    </button>
                </form>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                        <strong>Tip:</strong> Your feedback helps us improve our services. All feedback is reviewed by our administration team.
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Form submission
        document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const description = document.getElementById('description').value;
            if (description.length < 20) {
                showAlert('Feedback must be at least 20 characters long', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('feedback_type', document.getElementById('feedback_type').value);
            formData.append('department_office', document.getElementById('department_office').value);
            formData.append('description', description);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('process_feedback.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('Feedback submitted successfully! Reference: ' + data.reference_number, 'success');
                    setTimeout(() => {
                        window.location.href = 'my_submissions.php';
                    }, 2000);
                } else {
                    showAlert(data.message, 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                showAlert('Error submitting feedback. Please try again.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
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
            alertDiv.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
