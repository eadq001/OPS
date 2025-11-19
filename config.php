<?php
/**
 * CONFIGURATION FILE
 * Dela Salle John Bosco College - Student Complaint Portal
 * 
 * Edit this file to customize system behavior
 */

// ==================== DATABASE CONFIGURATION ====================
const CONFIG_DB_HOST = 'localhost';
const CONFIG_DB_USER = 'root';
const CONFIG_DB_PASSWORD = '';
const CONFIG_DB_NAME = 'delasalle_complaints';
const CONFIG_DB_PORT = 3306;

// ==================== SYSTEM CONFIGURATION ====================

// Timezone
const CONFIG_TIMEZONE = 'Asia/Manila';

// Session timeout (in seconds)
const CONFIG_SESSION_TIMEOUT = 3600; // 1 hour

// ==================== FILE UPLOAD CONFIGURATION ====================

// Maximum file size (in bytes)
const CONFIG_MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

// Allowed file types
const CONFIG_ALLOWED_FILETYPES = ['jpg', 'jpeg', 'png', 'pdf'];

// Upload directory
const CONFIG_UPLOAD_DIR = 'uploads/complaints/';

// ==================== COMPLAINT CONFIGURATION ====================

// Complaint types
const CONFIG_COMPLAINT_TYPES = [
    'Academic' => 'Academic Issues',
    'Facility' => 'Facility Problems',
    'Staff' => 'Staff Conduct',
    'Misconduct' => 'Student Misconduct',
    'Others' => 'Others'
];

// Complaint statuses
const CONFIG_COMPLAINT_STATUSES = [
    'Submitted' => 'Submitted',
    'Under Review' => 'Under Review',
    'In Progress' => 'In Progress',
    'Resolved' => 'Resolved',
    'Closed' => 'Closed',
    'Rejected' => 'Rejected'
];

// ==================== FEEDBACK CONFIGURATION ====================

// Feedback types
const CONFIG_FEEDBACK_TYPES = [
    'Suggestion' => 'Suggestion for Improvement',
    'Compliment' => 'Compliment',
    'General Comment' => 'General Comment'
];

// Departments for feedback
const CONFIG_DEPARTMENTS = [
    'Academic Affairs' => 'Academic Affairs',
    'Registrar' => "Registrar's Office",
    'Student Services' => 'Student Services',
    'Facilities' => 'Facilities Management',
    'Library' => 'Library Services',
    'Cafeteria' => 'Cafeteria',
    'IT Services' => 'IT Services',
    'General Administration' => 'General Administration',
    'Other' => 'Other'
];

// ==================== NOTIFICATION CONFIGURATION ====================

// Enable email notifications (requires PHPMailer)
const CONFIG_EMAIL_NOTIFICATIONS = false;

// SMTP Configuration (if email enabled)
const CONFIG_SMTP_HOST = 'smtp.gmail.com';
const CONFIG_SMTP_PORT = 587;
const CONFIG_SMTP_USERNAME = 'your_email@gmail.com';
const CONFIG_SMTP_PASSWORD = 'your_app_password';
const CONFIG_SMTP_FROM = 'noreply@delasalle.edu.ph';

// ==================== SECURITY CONFIGURATION ====================

// Minimum password length
const CONFIG_MIN_PASSWORD_LENGTH = 8;

// Username requirements: letters, numbers, underscores only
const CONFIG_USERNAME_PATTERN = '/^[a-zA-Z0-9_]{5,20}$/';
const CONFIG_USERNAME_MIN = 5;
const CONFIG_USERNAME_MAX = 20;

// ==================== PAGINATION ====================

// Records per page
const CONFIG_RECORDS_PER_PAGE = 10;

// ==================== LOGGING ====================

// Enable debug logging
const CONFIG_DEBUG_MODE = false;

// Log file location
const CONFIG_LOG_FILE = '../logs/system.log';

// ==================== API RESPONSE ====================

// API response format (json|xml)
const CONFIG_API_FORMAT = 'json';

// ==================== COMPANY INFORMATION ====================

const CONFIG_SCHOOL_NAME = 'Dela Salle John Bosco College';
const CONFIG_SCHOOL_EMAIL = 'support@delasalle.edu.ph';
const CONFIG_SCHOOL_PHONE = '+63 (2) 1234-5678';
const CONFIG_SCHOOL_ADDRESS = 'Manila, Philippines';

// ==================== MAINTENANCE ====================

// Maintenance mode
const CONFIG_MAINTENANCE_MODE = false;

// Backup settings
const CONFIG_AUTO_BACKUP = true;
const CONFIG_BACKUP_FREQUENCY = 'daily'; // daily, weekly, monthly

// ==================== HELPER FUNCTIONS ====================

/**
 * Get configuration value with fallback
 */
function get_config($key, $default = null) {
    $key = 'CONFIG_' . strtoupper($key);
    return defined($key) ? constant($key) : $default;
}

/**
 * Check if feature is enabled
 */
function is_feature_enabled($feature) {
    $key = 'CONFIG_' . strtoupper($feature);
    return defined($key) && constant($key);
}

/**
 * Get complaint type label
 */
function get_complaint_type_label($type) {
    return CONFIG_COMPLAINT_TYPES[$type] ?? $type;
}

/**
 * Get status label
 */
function get_status_label($status) {
    return CONFIG_COMPLAINT_STATUSES[$status] ?? $status;
}

/**
 * Get department label
 */
function get_department_label($dept) {
    return CONFIG_DEPARTMENTS[$dept] ?? $dept;
}

// ==================== VALIDATION FUNCTIONS ====================

/**
 * Validate username
 */
function validate_username($username) {
    if (strlen($username) < CONFIG_USERNAME_MIN || strlen($username) > CONFIG_USERNAME_MAX) {
        return false;
    }
    return preg_match(CONFIG_USERNAME_PATTERN, $username);
}

/**
 * Validate password
 */
function validate_password($password) {
    return strlen($password) >= CONFIG_MIN_PASSWORD_LENGTH;
}

/**
 * Validate email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate file upload
 */
function validate_file_upload($file) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($file['size'] > CONFIG_MAX_FILE_SIZE) {
        return ['valid' => false, 'error' => 'File too large'];
    }
    
    if (!in_array($ext, CONFIG_ALLOWED_FILETYPES)) {
        return ['valid' => false, 'error' => 'File type not allowed'];
    }
    
    return ['valid' => true];
}

?>
