# Dela Salle John Bosco College - Student Complaint & Feedback Portal

## System Overview
This is a comprehensive web-based portal for managing student complaints and feedback at Dela Salle John Bosco College. The system includes automatic logging, monitoring, and systematic addressing of student grievances.

## Features

### For Students
- **User Authentication**: Secure login, registration, and password recovery
- **Dashboard**: Quick access to all submission features and announcements
- **Submit Complaints**: File complaints with automatic reference number generation
  - Types: Academic, Facility, Staff, Misconduct, Others
  - File upload support for evidence (images, PDF)
  - Real-time confirmation notifications
  
- **Submit Feedback**: Share suggestions, compliments, and general comments
- **Track Submissions**: View all submitted complaints and feedback with status updates
- **Notification System**: Receive portal notifications for status changes
- **Profile Management**: Update personal information and change password

### For Admin/Staff
- **Admin Dashboard**: Overview with statistics and recent submissions
- **Complaint Management**: 
  - View all complaints with filtering and sorting
  - Update status and add remarks
  - Assign to appropriate departments
  - Track status history
  
- **Feedback Management**: Monitor and acknowledge feedback
- **Audit Logging**: Track all system activities
- **Report Generation**: Monthly and custom reports (coming soon)
- **Announcements**: Post system-wide announcements

## System Architecture

### Database
- **Users**: Student and admin accounts
- **Complaints**: Main complaint records
- **Feedback**: Feedback submissions
- **Notifications**: Portal notification system
- **Audit Log**: System activity tracking
- **Status History**: Track complaint status changes
- **Admin Remarks**: Track admin responses

### Key Files

#### Core Files
- `db_connection.php` - Single database connection with utility functions
- `index.php` - Entry point that redirects based on user type

#### Authentication (Login/Register/Password Reset)
- `login.php` - Student/Admin login page
- `process_login.php` - Login processing
- `register.php` - Student registration
- `process_register.php` - Registration processing
- `forgot_password.php` - Password recovery
- `process_forgot_password.php` - Password reset processing

#### Student Pages
- `student_dashboard.php` - Main dashboard
- `get_dashboard_data.php` - Dashboard data API
- `submit_complaint.php` - Complaint submission form
- `process_complaint.php` - Complaint processing
- `submit_feedback.php` - Feedback submission form
- `process_feedback.php` - Feedback processing
- `my_submissions.php` - View submitted items
- `get_submissions.php` - Submissions data API
- `view_details.php` - View complaint/feedback details
- `get_detail.php` - Detail data API
- `notifications.php` - Notification center
- `get_notifications.php` - Notifications API
- `mark_notification_read.php` - Mark notification as read
- `mark_all_read.php` - Mark all as read
- `profile.php` - Profile settings
- `get_profile.php` - Profile data API
- `update_profile.php` - Update profile
- `change_password.php` - Change password
- `logout.php` - Logout handler

#### Admin Pages
- `admin_dashboard.php` - Admin main dashboard
- `get_admin_dashboard.php` - Admin dashboard data
- `admin_complaints.php` - Manage all complaints
- `get_all_complaints.php` - All complaints API
- `admin_view_complaint.php` - View complaint details
- `admin_get_complaint.php` - Complaint detail API
- `admin_update_complaint.php` - Update complaint status
- `admin_feedback.php` - Feedback management (placeholder)
- `admin_reports.php` - Reports (placeholder)
- `admin_announcements.php` - Announcements (placeholder)
- `admin_audit_log.php` - Audit log (placeholder)

#### Database
- `database.sql` - SQL schema and sample data

## Installation & Setup

### 1. Database Setup
```sql
-- Create database
CREATE DATABASE delasalle_complaints;

-- Import the schema
SOURCE database.sql;
```

### 2. Configure Database Connection
Edit `db_connection.php` and update:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'delasalle_complaints');
```

### 3. Create Upload Directory
```bash
mkdir -p uploads/complaints
chmod 755 uploads/complaints
```

### 4. Deployment
- Copy all files to your web server (Apache, Nginx, etc.)
- Ensure PHP 7.4+ is installed
- Configure your web server to point to this directory

## Default Credentials

### Admin Account
- **Username**: admin
- **Password**: admin123

### Student Account
- **Username**: student001
- **Password**: student123

⚠️ **IMPORTANT**: Change these credentials immediately in production!

## Usage

### For Students
1. Go to `login.php` or access the index
2. Register a new account or login
3. Access dashboard
4. Submit complaints or feedback
5. Track submissions and receive notifications

### For Admin
1. Login with admin credentials
2. Access admin dashboard
3. Review complaints and feedback
4. Update status and add remarks
5. Monitor system activity via audit log

## Security Features

- ✅ Password hashing using bcrypt
- ✅ SQL prepared statements (prevent SQL injection)
- ✅ Input sanitization
- ✅ Session management with timeout
- ✅ Audit logging of all actions
- ✅ Role-based access control (RBAC)
- ✅ File upload validation
- ✅ CSRF protection ready

## Responsive Design

- ✅ Mobile-friendly using Tailwind CSS
- ✅ Responsive navigation
- ✅ Optimized for all screen sizes
- ✅ Touch-friendly interface

## API Endpoints (AJAX)

### Student APIs
- `GET /get_dashboard_data.php` - Dashboard stats
- `GET /get_submissions.php` - User submissions
- `GET /get_detail.php?id=X&type=Y` - Submission details
- `GET /get_notifications.php` - User notifications
- `GET /get_profile.php` - User profile
- `POST /process_complaint.php` - Submit complaint
- `POST /process_feedback.php` - Submit feedback
- `POST /update_profile.php` - Update profile
- `POST /change_password.php` - Change password

### Admin APIs
- `GET /get_admin_dashboard.php` - Admin dashboard
- `GET /get_all_complaints.php` - All complaints
- `GET /admin_get_complaint.php?id=X` - Complaint detail
- `POST /admin_update_complaint.php` - Update complaint

## File Structure
```
OPS/
├── db_connection.php
├── database.sql
├── index.php
├── login.php
├── register.php
├── forgot_password.php
├── student_dashboard.php
├── submit_complaint.php
├── submit_feedback.php
├── my_submissions.php
├── view_details.php
├── notifications.php
├── profile.php
├── admin_dashboard.php
├── admin_complaints.php
├── admin_feedback.php
├── admin_reports.php
├── admin_announcements.php
├── admin_audit_log.php
├── uploads/
│   └── complaints/
└── [Various PHP processing files]
```

## Important Notes

1. **Email Functionality**: Currently email features are simulated. Integrate PHPMailer for actual email sending.

2. **Time Zone**: Set to 'Asia/Manila' in db_connection.php. Modify as needed.

3. **Session Timeout**: Set to 1 hour. Adjust in db_connection.php if needed.

4. **File Upload Limits**: Max 5MB per file, allowed types: JPG, PNG, PDF

5. **Database Backups**: Regularly backup the database for data safety.

## Troubleshooting

### Database Connection Error
- Check DB credentials in `db_connection.php`
- Ensure MySQL service is running
- Verify database and tables exist

### Upload Failures
- Check folder permissions: `chmod 755 uploads/`
- Verify disk space
- Check PHP file upload limits in php.ini

### Session Issues
- Clear browser cookies
- Check PHP session handler configuration
- Verify session save path permissions

## Enhancement Opportunities

1. **Email Integration**: Implement actual email notifications using PHPMailer
2. **Advanced Reports**: PDF report generation with charts
3. **Email Alerts**: Notify admins of new complaints
4. **SMS Notifications**: Add SMS alerts (optional)
5. **Department Routing**: Auto-assign based on complaint type
6. **Analytics Dashboard**: Enhanced admin analytics
7. **Mobile App**: Companion mobile application
8. **Multi-language Support**: Support multiple languages

## Support & Maintenance

For issues or feature requests:
- Check error logs in PHP error_log
- Review audit_log table for activity history
- Validate database integrity regularly
- Monitor system performance

## License

This system is proprietary to Dela Salle John Bosco College.

---

**System Version**: 1.0  
**Last Updated**: November 2025
