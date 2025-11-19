<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - DSJBC Student Complaint Portal</title>
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
                <a href="submit_complaint.php" class="block px-4 py-2 rounded-lg bg-blue-700">
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
            <a href="student_dashboard.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold mb-6">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 max-w-2xl">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-file-alt mr-3 text-red-600"></i>Submit Complaint
                </h1>
                <p class="text-gray-600 mb-6">Describe your issue in detail so we can address it promptly.</p>

                <!-- Alert Messages -->
                <div id="alert" class="mb-6"></div>

                <form id="complaintForm" method="POST" enctype="multipart/form-data" action="process_complaint.php">
                    <!-- Complaint Type -->
                    <div class="mb-6">
                        <label for="complaint_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Type of Complaint
                        </label>
                        <select id="complaint_type" name="complaint_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select complaint type...</option>
                            <option value="Academic">Academic Issues</option>
                            <option value="Facility">Facility Problems</option>
                            <option value="Staff">Staff Conduct</option>
                            <option value="Misconduct">Student Misconduct</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <!-- Course / Department -->
                    <div class="mb-6">
                        <label for="course_department" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Course / Department
                        </label>
                        <input type="text" id="course_department" name="course_department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your course or department" value="<?php echo htmlspecialchars($_SESSION['course_department'] ?? ''); ?>" required>
                    </div>

                    <!-- Date of Incident -->
                    <div class="mb-6">
                        <label for="date_incident" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Date of Incident
                        </label>
                        <input type="date" id="date_incident" name="date_incident" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <!-- Detailed Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-600">*</span> Detailed Description
                        </label>
                        <textarea id="description" name="description" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Please provide a detailed description of your complaint..." required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 20 characters required</p>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="evidence" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Evidence (Optional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition" id="dropZone">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Drag and drop files here or click to browse</p>
                            <p class="text-xs text-gray-500 mt-1">Supported: JPG, PNG, PDF (Max 5MB each)</p>
                            <input type="file" id="evidence" name="evidence[]" multiple class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        <div id="fileList" class="mt-3 space-y-2"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Complaint
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Get today's date and set as max
        document.getElementById('date_incident').max = new Date().toISOString().split('T')[0];

        // File upload handling
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('evidence');
        const fileList = document.getElementById('fileList');
        let selectedFiles = [];

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            for (let file of files) {
                if (!selectedFiles.find(f => f.name === file.name)) {
                    if (file.size <= 5 * 1024 * 1024) {
                        selectedFiles.push(file);
                    } else {
                        showAlert('File ' + file.name + ' is too large (max 5MB)', 'error');
                    }
                }
            }
            updateFileList();
        }

        function updateFileList() {
            fileList.innerHTML = selectedFiles.map((file, index) => `
                <div class="flex items-center justify-between bg-gray-100 p-2 rounded">
                    <span class="text-sm text-gray-700">
                        <i class="fas fa-file mr-2"></i>${file.name}
                    </span>
                    <button type="button" class="text-red-600 hover:text-red-700" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
        }

        // Form submission
        document.getElementById('complaintForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const description = document.getElementById('description').value;
            if (description.length < 20) {
                showAlert('Description must be at least 20 characters long', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('complaint_type', document.getElementById('complaint_type').value);
            formData.append('course_department', document.getElementById('course_department').value);
            formData.append('date_incident', document.getElementById('date_incident').value);
            formData.append('description', description);

            selectedFiles.forEach(file => {
                formData.append('evidence[]', file);
            });

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('process_complaint.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('Complaint submitted successfully! Reference: ' + data.reference_number, 'success');
                    setTimeout(() => {
                        window.location.href = 'my_submissions.php';
                    }, 2000);
                } else {
                    showAlert(data.message, 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                showAlert('Error submitting complaint. Please try again.', 'error');
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
