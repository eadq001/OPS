# Quick Reference Guide - Student Complaint Portal

## For Administrators

### Access Admin Dashboard
1. Go to: `http://yoursite.com/` (or `http://localhost/`)
2. Log in with admin credentials
3. You'll be redirected to `/admin_dashboard.php`

### Common Admin Tasks

#### View All Complaints
1. Click "Complaints" in sidebar
2. Page shows all complaints from all students
3. Use filters to narrow results:
   - **Status**: Submitted, Under Review, In Progress, Resolved, Closed, Rejected
   - **Type**: Academic, Facility, Staff, Misconduct, Others
   - **Department**: Course/department of complainant
4. Click "Review" to see complaint details

#### Update Complaint Status
1. From "Complaints" page, click "Review" button
2. Scroll down to "Admin Response" section
3. Select new status from dropdown
4. Add remarks (optional but recommended)
5. Click "Submit Response"
6. Student will receive automatic notification

#### View Complaint Details
- **File Downloads**: Click filename to download attached document
- **Status History**: View timeline of all status changes
- **Previous Remarks**: See all admin responses in chronological order
- **Student Info**: Name, email, phone, course, department

#### Manage Feedback
- Click "Feedback" in sidebar (currently placeholder)
- Future enhancement to manage student feedback similar to complaints

#### Generate Reports
- Click "Reports" in sidebar (currently placeholder)
- Future enhancement for monthly/custom reports
- Will support PDF export and statistics

#### View Announcements
- Click "Announcements" in sidebar (currently placeholder)
- Future enhancement to create college-wide announcements
- Students see announcements on dashboard

#### View Audit Log
- Click "Audit Log" in sidebar (currently placeholder)
- Future enhancement to view all user activities
- Current data is logged in database audit_log table

---

## For Students

### Create Student Account
1. Go to: `http://yoursite.com/login.php`
2. Click "Don't have an account? Register here"
3. Fill in registration form:
   - First Name
   - Last Name
   - Email (must be unique)
   - Phone
   - Course (dropdown)
   - Department (dropdown)
   - Username (5-20 characters, alphanumeric only)
   - Password (minimum 8 characters)
4. Click "Register"
5. You'll be redirected to login page
6. Log in with your credentials

### Access Student Dashboard
1. Log in with your student account
2. You'll see dashboard with:
   - Quick action buttons (Submit Complaint, Submit Feedback, My Submissions)
   - Statistics cards showing your submission counts
   - Recent announcements
   - Your recent submissions

### Submit a Complaint
1. From dashboard, click "Submit Complaint" or use sidebar
2. Fill in the form:
   - **Complaint Type**: Select from Academic, Facility, Staff, Misconduct, Others
   - **Course/Department**: Your course or department
   - **Date of Incident**: When the issue occurred
   - **Description**: Detailed explanation (minimum 20 characters)
   - **Attachments**: Upload supporting documents (JPG, PNG, PDF, max 5MB per file)
3. Click "Submit Complaint"
4. You'll receive a Reference Number to track your complaint
5. Save this number for future reference

### Submit Feedback
1. From dashboard, click "Submit Feedback" or use sidebar
2. Fill in the form:
   - **Feedback Type**: Select Suggestion, Compliment, or General Comment
   - **Department/Office**: Select target department
   - **Message**: Your feedback
3. Click "Submit Feedback"
4. You'll receive a Reference Number

### Track Your Complaints & Feedback
1. Click "My Submissions" or "View Submissions" in sidebar
2. You can:
   - **Filter** by type (Complaints/Feedback) or status (Submitted/Under Review/etc)
   - **Sort** by newest or oldest
   - **View Details** by clicking the reference number or "View" button
3. In detail view, you can see:
   - Full complaint/feedback text
   - Attached documents with download links
   - Admin remarks and responses
   - Complete status history with dates

### Check Notifications
1. Click "Notifications" in sidebar
2. You'll see notifications about:
   - Status changes on your complaints
   - Admin remarks/responses
   - Other system messages
3. Unread notifications appear with blue badge
4. Click notification to mark as read
5. Use "Mark All as Read" to clear all notifications
6. Filter by notification type (optional)

### Manage Your Profile
1. Click "Profile" in sidebar or your name in header
2. You can:
   - **Edit Profile**: Update first name, last name, email, phone, course, department
   - **Change Password**: Enter current password, then new password (min 8 characters)
3. Changes save immediately
4. You'll be logged out if password changes - re-login with new password

### View Announcements
- Announcements appear on your dashboard
- Check regularly for important college updates
- Scroll through to see all announcements

### Logout
1. Click "Logout" in sidebar or top-right menu
2. Your session will be cleared
3. You'll be redirected to login page

---

## Troubleshooting

### I forgot my password
1. Go to login page
2. Click "Forgot Password?"
3. Enter your email or username
4. Check your email for reset instructions
5. (Note: In current version, reset link is displayed on screen - production uses email)

### I can't log in
**Possible causes:**
- Wrong username or password - verify caps lock is off
- Account not created - go to register page
- Account suspended - contact administrator
- Server/database issue - try again after a few minutes

**Solution:**
1. Verify username and password are correct
2. If unsure, use "Forgot Password" to reset
3. If still having issues, contact support

### My file upload failed
**Possible causes:**
- File is too large (max 5MB)
- File type not allowed (only JPG, PNG, PDF)
- Upload directory doesn't exist or isn't writable
- Network connection interrupted

**Solutions:**
1. Check file size - try compressing images or PDF
2. Check file extension - must be .jpg, .png, or .pdf
3. Try uploading to a different complaint first
4. If problem persists, contact support

### I don't see my recent submission
**Possible causes:**
- Form didn't submit successfully
- Page didn't refresh after submission
- You're looking at the wrong section (Complaints vs Feedback)
- Filter is hiding your submission

**Solutions:**
1. Check for error message at bottom of form
2. Try submitting again
3. Clear all filters ("Filter" button) to show all submissions
4. Check both "Complaints" and "Feedback" sections
5. Refresh page and check again

### I received a notification but can't see it
1. Check "Notifications" section
2. Scroll through all pages of notifications
3. Use filter to find specific notification type
4. Check if filter is limiting displayed notifications
5. Refresh page to reload notifications

### Status shows as "Under Review" but nothing has happened
- This is normal - admin may be investigating
- Check notification center for admin remarks
- Reviews typically take 2-5 business days
- You'll receive notification when status updates

### Admin says they updated my complaint but I don't see it
1. Refresh the page (Ctrl+F5 on Windows, Cmd+Shift+R on Mac)
2. Check Notifications - you should have received alert
3. Check details page - scroll down for admin remarks
4. Check Status History section for update timeline
5. If still not visible, contact support

---

## Default Test Accounts

### Admin Account
- **Username**: admin
- **Password**: admin123
- **Role**: Full admin access

### Student Account
- **Username**: student001
- **Password**: student123
- **Role**: Student access (complaints, feedback, notifications)

**IMPORTANT**: Change these passwords immediately in production!

---

## Database Quick Reference

### Connect to Database
```bash
mysql -h localhost -u complaint_user -p delasalle_complaints
```

### Useful Queries

#### Check total complaints
```sql
SELECT COUNT(*) as total_complaints FROM complaints;
```

#### Check complaints by status
```sql
SELECT status, COUNT(*) as count FROM complaints GROUP BY status;
```

#### Find specific student's complaints
```sql
SELECT * FROM complaints WHERE user_id = 1 ORDER BY created_at DESC;
```

#### Find unread notifications
```sql
SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC;
```

#### View recent activity (audit log)
```sql
SELECT * FROM audit_log ORDER BY action_timestamp DESC LIMIT 50;
```

#### Update complaint status
```sql
UPDATE complaints SET status = 'Resolved' WHERE complaint_id = 5;
```

#### View status history for complaint
```sql
SELECT * FROM status_history WHERE complaint_id = 5 ORDER BY changed_at;
```

---

## File Structure Reference

### Important Directories
```
/uploads/complaints/  - All uploaded complaint files
/logs/                - System logs (if enabled)
```

### Key Files
```
db_connection.php     - Database connection and utilities
config.php            - System configuration
index.php             - Entry point (redirects by role)
```

### Student Files
```
student_dashboard.php         - Student main page
submit_complaint.php          - Complaint form
submit_feedback.php           - Feedback form
my_submissions.php            - View complaints/feedback
view_details.php              - Detail view
notifications.php             - Notification center
profile.php                   - Profile management
```

### Admin Files
```
admin_dashboard.php           - Admin main page
admin_complaints.php          - Complaint management
admin_view_complaint.php      - Complaint detail
admin_feedback.php            - Feedback management (placeholder)
admin_reports.php             - Report generation (placeholder)
admin_announcements.php       - Announcement management (placeholder)
admin_audit_log.php           - Audit log viewer (placeholder)
```

---

## Important Configuration Points

### To Change System Name
Edit `config.php`:
```php
const CONFIG_SCHOOL_NAME = 'Your School Name';
const CONFIG_SCHOOL_EMAIL = 'your_email@school.edu';
```

### To Change Session Timeout
Edit `config.php`:
```php
const CONFIG_SESSION_TIMEOUT = 3600; // seconds (1 hour)
```

### To Change Max File Size
Edit `config.php`:
```php
const CONFIG_MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
```

### To Enable Debug Mode
Edit `config.php`:
```php
const CONFIG_DEBUG_MODE = true;
```

### To Configure Email (Optional)
Edit `config.php`:
```php
const CONFIG_EMAIL_NOTIFICATIONS = true;
const CONFIG_SMTP_HOST = 'smtp.gmail.com';
const CONFIG_SMTP_PORT = 587;
const CONFIG_SMTP_USERNAME = 'your_email@gmail.com';
const CONFIG_SMTP_PASSWORD = 'your_app_password';
```

---

## Performance Tips

### Improve Database Performance
1. Ensure all indexes are created (from database.sql)
2. Run regular OPTIMIZE TABLE on larger tables
3. Archive old complaints to separate table annually
4. Monitor slow query log

### Improve Page Load Speed
1. Enable browser caching
2. Minify CSS and JavaScript
3. Compress images in complaints
4. Use CDN for static assets
5. Enable gzip compression on server

### Monitor System Health
1. Check disk space monthly
2. Verify backups are completing
3. Monitor database size growth
4. Review error logs for issues
5. Monitor server CPU and RAM usage

---

## Support & Escalation

### For Technical Issues
Contact: support@delasalle.edu.ph
Provide: Error message, steps to reproduce, browser/OS info

### For Admin Issues
Contact: admin@delasalle.edu.ph
Provide: User/complaint ID, what you were trying to do, result

### For Database Issues
Contact: DBA team
Provide: Query that failed, time of occurrence, affected records

### For Security Issues
Contact: IT Security immediately
Provide: Description of issue, time of occurrence, any suspicious activity

---

*Last Updated: 2024*
*Document Version: 1.0*
