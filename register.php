<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DSJBC Student Complaint Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8 px-4">
    <div class="max-w-md mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="login.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Login
            </a>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Student Account</h2>

            <!-- Alert Messages -->
            <div id="alert" class="mb-4"></div>

            <form id="registerForm" method="POST" action="process_register.php">
                <!-- First Name -->
                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter first name" required>
                </div>

                <!-- Last Name -->
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter last name" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter email address" required>
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter phone number">
                </div>

                <!-- Course -->
                <div class="mb-4">
                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course / Program</label>
                    <select id="course" name="course" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Course</option>
                        <option value="BS Computer Science">BS Computer Science</option>
                        <option value="BS Information Technology">BS Information Technology</option>
                        <option value="BS Business Administration">BS Business Administration</option>
                        <option value="BS Education">BS Education</option>
                        <option value="BS Engineering">BS Engineering</option>
                        <option value="BS Nursing">BS Nursing</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Department -->
                <div class="mb-4">
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" id="department" name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter department">
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Choose username" required>
                    <p class="text-xs text-gray-500 mt-1">Must be 5-20 characters, letters and numbers only</p>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Create password" required>
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm password" required>
                </div>

                <!-- Register Button -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>

                <!-- Already have account -->
                <p class="text-center text-gray-600 text-sm mt-4">
                    Already have an account? <a href="login.php" class="text-blue-600 hover:text-blue-700 font-semibold">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Validate passwords match
            if (password !== confirmPassword) {
                showAlert('Passwords do not match!', 'error');
                return;
            }

            // Validate password length
            if (password.length < 8) {
                showAlert('Password must be at least 8 characters long!', 'error');
                return;
            }

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Registering...';
            submitBtn.disabled = true;

            fetch('process_register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showAlert(data.message || data.error || 'An error occurred.', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred. Please try again. (' + error.message + ')', 'error');
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
    </script>
</body>
</html>
