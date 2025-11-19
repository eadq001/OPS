<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - DSJBC Student Complaint Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="login.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Login
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Reset Your Password</h2>
            <p class="text-gray-600 text-sm mb-6">Enter your username or email address and we'll send you instructions to reset your password.</p>

            <!-- Alert Messages -->
            <div id="alert" class="mb-4"></div>

            <form id="forgotForm" method="POST" action="process_forgot_password.php">
                <!-- Username or Email -->
                <div class="mb-4">
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Username or Email
                    </label>
                    <input type="text" id="identifier" name="identifier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter username or email" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition mb-3">
                    <i class="fas fa-envelope mr-2"></i>Send Reset Instructions
                </button>

                <!-- Divider -->
                <div class="my-4 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <div class="px-3 text-gray-500 text-sm">OR</div>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <!-- Links -->
                <a href="login.php" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Back to Login
                </a>
            </form>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-700">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <strong>Note:</strong> Check your email for reset instructions. If you don't receive an email within 5 minutes, check your spam folder.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const identifier = document.getElementById('identifier').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            submitBtn.disabled = true;

            fetch('process_forgot_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'identifier=' + encodeURIComponent(identifier)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 3000);
                } else {
                    showAlert(data.message, 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                showAlert('An error occurred. Please try again.', 'error');
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
