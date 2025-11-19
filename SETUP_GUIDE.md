# SETUP GUIDE - DSJBC Student Complaint Portal

## Quick Start (5 Minutes)

### Step 1: Database Setup
1. Open MySQL/phpMyAdmin
2. Create new database: `delasalle_complaints`
3. Import `database.sql` file
4. Verify tables created successfully

### Step 2: Configure Connection
1. Edit `db_connection.php`
2. Update these lines with your database credentials:
```php
define('DB_USER', 'root');
define('DB_PASSWORD', 'your_password');
```

### Step 3: Create Upload Directory
```bash
mkdir -p uploads/complaints
chmod 755 uploads/complaints
```

### Step 4: Access the System
- **Students**: http://localhost/OPS/login.php
- **Admin**: http://localhost/OPS/admin_dashboard.php

---

## Detailed Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web Server (Apache/Nginx)
- 50MB disk space minimum

### Installation Steps

#### 1. Database Setup
```sql
-- Login to MySQL
mysql -u root -p

-- Create database
CREATE DATABASE delasalle_complaints CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use database
USE delasalle_complaints;

-- Import schema (paste content from database.sql)
-- OR use command line:
-- mysql -u root -p delasalle_complaints < database.sql
```

#### 2. File Structure
```
c:\path\to\OPS\
├── db_connection.php          ✓ Database config & utilities
├── database.sql               ✓ Schema & sample data
├── index.php                  ✓ Entry point
│
├── LOGIN SYSTEM
├── login.php
├── register.php
├── forgot_password.php
├── process_login.php
├── process_register.php
├── process_forgot_password.php
│
├── STUDENT PAGES
├── student_dashboard.php
├── submit_complaint.php
├── submit_feedback.php
├── my_submissions.php
├── view_details.php
├── notifications.php
├── profile.php
├── logout.php
│
├── ADMIN PAGES
├── admin_dashboard.php
├── admin_complaints.php
├── admin_view_complaint.php
├── admin_feedback.php
├── admin_reports.php
├── admin_announcements.php
├── admin_audit_log.php
│
├── API FILES (AJAX)
├── get_dashboard_data.php
├── get_submissions.php
├── get_detail.php
├── get_notifications.php
├── get_profile.php
├── get_admin_dashboard.php
├── get_all_complaints.php
├── admin_get_complaint.php
├── (other processing files)
│
└── uploads/
    └── complaints/          ✓ Must have write permissions
```

#### 3. Permissions
```bash
# Linux/Mac
chmod 755 uploads/complaints
chmod 644 *.php

# Windows: Right-click folder → Properties → Security → Edit
# Grant "Modify" permission to IIS_APPPOOL or apache user
```

#### 4. Web Server Configuration

**Apache (.htaccess)**
```apache
RewriteEngine On
RewriteBase /OPS/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]
```

**Nginx**
```nginx
location /OPS/ {
    try_files $uri $uri/ /OPS/index.php?url=$request_uri;
}
```

---

## Testing the System

### Test Login (Student)
```
URL: http://localhost/OPS/login.php
Username: student001
Password: student123
```

### Test Login (Admin)
```
URL: http://localhost/OPS/admin_dashboard.php
Username: admin
Password: admin123
```

### Features to Test
- ✓ User registration with validation
- ✓ Login with "Remember Me"
- ✓ Submit complaint with file upload
- ✓ Submit feedback
- ✓ View submissions with filters
- ✓ View complaint details
- ✓ Receive notifications
- ✓ Update profile
- ✓ Change password
- ✓ Admin viewing complaints
- ✓ Admin updating status with remarks
- ✓ Audit log entries

---

## Production Deployment Checklist

### Security
- [ ] Change default admin and student passwords
- [ ] Set strong database password
- [ ] Enable HTTPS/SSL
- [ ] Configure firewall rules
- [ ] Set file permissions (644 for files, 755 for directories)
- [ ] Disable directory listing
- [ ] Set PHP error reporting to production mode

### Performance
- [ ] Enable database query caching
- [ ] Configure PHP opcache
- [ ] Implement CDN for static files
- [ ] Set appropriate session timeout
- [ ] Regular database maintenance

### Backups
- [ ] Setup automated daily backups
- [ ] Test backup restoration
- [ ] Store backups offsite
- [ ] Document recovery procedures

### Monitoring
- [ ] Setup error logging
- [ ] Monitor system performance
- [ ] Track database growth
- [ ] Review audit logs regularly

---

## Common Issues & Solutions

### Issue 1: "Database Connection Failed"
**Solution:**
```php
// Check db_connection.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'correct_password');
define('DB_NAME', 'delasalle_complaints');

// Test connection
$test_conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
echo $test_conn->connect_error;
```

### Issue 2: "File Upload Failed"
**Solution:**
```bash
# Check permissions
ls -la uploads/complaints/

# Should show: drwxr-xr-x (755)

# Fix permissions
chmod 755 uploads/complaints
chmod 777 uploads/complaints/  # If still failing

# Check PHP settings in php.ini
upload_max_filesize = 5M
post_max_size = 10M
```

### Issue 3: "Session Expires Too Quickly"
**Solution:**
```php
// In db_connection.php
ini_set('session.gc_maxlifetime', 3600);  // Increase value
ini_set('session.cookie_lifetime', 3600);

// Also check php.ini:
session.gc_maxlifetime = 1440
```

### Issue 4: "Notifications Not Showing"
**Solution:**
```bash
# Check audit_log table
SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10;

# Check if notifications are being created
SELECT COUNT(*) FROM notifications WHERE user_id = 1;
```

---

## Email Configuration (Optional)

To enable actual email notifications, install PHPMailer:

```bash
composer require phpmailer/phpmailer
```

Then update `db_connection.php`:
```php
use PHPMailer\PHPMailer\PHPMailer;

function send_email_notification($email, $subject, $message) {
    $mail = new PHPMailer(true);
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';
    $mail->Password = 'your_app_password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    $mail->setFrom('your_email@gmail.com');
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    return $mail->send();
}
```

---

## Admin Quick Reference

### Create New Admin User
```sql
INSERT INTO users (username, password, email, first_name, last_name, user_type, status)
VALUES ('newadmin', SHA2('password123', 256), 'admin@dsjbc.edu.ph', 'Admin', 'Name', 'admin', 'active');
```

### Reset Student Password
```sql
UPDATE users 
SET password = SHA2('newpassword', 256)
WHERE username = 'student001';
```

### View Recent Complaints
```sql
SELECT c.reference_number, c.complaint_type, c.status, c.created_at
FROM complaints c
ORDER BY c.created_at DESC
LIMIT 10;
```

### Get Statistics
```sql
SELECT 
    (SELECT COUNT(*) FROM complaints) as total_complaints,
    (SELECT COUNT(*) FROM complaints WHERE status = 'Resolved') as resolved,
    (SELECT COUNT(*) FROM feedback) as total_feedback;
```

---

## Support & Resources

### Documentation Files
- `README.md` - System overview
- `database.sql` - Database schema
- This file - Setup guide

### File Locations
- Logs: PHP error_log (check web server directory)
- Database: Local MySQL database
- Uploads: `uploads/complaints/`

### Troubleshooting Steps
1. Check error_log for PHP errors
2. Verify database tables: `SHOW TABLES;`
3. Check user permissions: `SELECT * FROM users;`
4. Review audit logs: `SELECT * FROM audit_log ORDER BY created_at DESC;`

---

## Version Information

- **System**: DSJBC Student Complaint Portal v1.0
- **PHP**: 7.4+
- **MySQL**: 5.7+
- **Last Updated**: November 2025

---

**Installation Date**: _______________  
**Admin Name**: _______________  
**Contact**: _______________
