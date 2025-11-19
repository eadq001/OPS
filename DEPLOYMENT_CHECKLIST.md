# Deployment Checklist - Student Complaint Portal

## Pre-Deployment (Development Environment)

### Testing
- [ ] Test all authentication flows (login, register, forgot password)
- [ ] Test complaint submission with file uploads
- [ ] Test feedback submission
- [ ] Test admin dashboard and filtering
- [ ] Test admin status updates and remarks
- [ ] Test notifications on status changes
- [ ] Verify all database constraints and relationships
- [ ] Test with multiple browsers (Chrome, Firefox, Safari, Edge)
- [ ] Test responsive design on mobile devices (iOS and Android)
- [ ] Verify all API endpoints return correct JSON
- [ ] Test password hashing and verification
- [ ] Verify audit logging is working
- [ ] Test session timeout after 1 hour of inactivity

### Code Review
- [ ] Remove debug statements and console.log() calls
- [ ] Verify no hardcoded passwords or credentials
- [ ] Check for SQL injection vulnerabilities (all queries use prepared statements)
- [ ] Verify CSRF protection is in place
- [ ] Check all user inputs are sanitized
- [ ] Verify error messages don't expose system information
- [ ] Check file upload functionality for security issues
- [ ] Verify all sensitive operations are logged

### Documentation
- [ ] Update README.md with production deployment instructions
- [ ] Document all custom functions and their parameters
- [ ] Create database backup procedure documentation
- [ ] Create recovery procedure documentation
- [ ] Document troubleshooting procedures

---

## Production Deployment

### Server Preparation

#### 1. Web Server Setup
- [ ] Apache/Nginx installed and configured
- [ ] PHP 7.4+ installed with required extensions:
  - [ ] mysqli
  - [ ] json
  - [ ] filter
  - [ ] hash
  - [ ] sessions
- [ ] Verify mod_rewrite enabled (if using Apache)
- [ ] Set proper file permissions (755 for directories, 644 for files)

#### 2. Database Setup
- [ ] MySQL 5.7+ installed and running
- [ ] Create production database: `delasalle_complaints`
- [ ] Create dedicated database user with limited privileges:
  ```sql
  CREATE USER 'complaint_user'@'localhost' IDENTIFIED BY 'strong_password';
  GRANT SELECT, INSERT, UPDATE ON delasalle_complaints.* TO 'complaint_user'@'localhost';
  FLUSH PRIVILEGES;
  ```
- [ ] Import schema from database.sql
- [ ] Verify all tables created successfully
- [ ] Set up database backups (at least daily)
- [ ] Test backup restoration procedure

#### 3. Directory Structure
- [ ] Create `/uploads/complaints/` directory with 755 permissions
- [ ] Create `/logs/` directory with 755 permissions (if using logging)
- [ ] Verify web server user has write permissions
- [ ] Set up `.gitignore` to exclude uploads and logs from version control

#### 4. Configuration
- [ ] Update `db_connection.php` with production credentials:
  - [ ] DB_HOST (if not localhost)
  - [ ] DB_USER (production user)
  - [ ] DB_PASSWORD (strong password)
  - [ ] DB_NAME
- [ ] Update `config.php` settings:
  - [ ] Set CONFIG_DEBUG_MODE = false
  - [ ] Configure CONFIG_SMTP for email (if using email notifications)
  - [ ] Set CONFIG_SCHOOL_NAME and contact info
  - [ ] Configure CONFIG_SESSION_TIMEOUT
- [ ] Remove default test users and create production admin account

### Security

#### 5. HTTPS/SSL
- [ ] Install SSL certificate (Let's Encrypt free or paid)
- [ ] Configure web server to use HTTPS
- [ ] Force HTTP to HTTPS redirect
- [ ] Set HSTS header for security
- [ ] Verify certificate is valid and not self-signed

#### 6. Database Security
- [ ] Remove default MySQL users (test, anonymous)
- [ ] Change MySQL root password
- [ ] Disable MySQL network access if on same server
- [ ] Set up firewall rules to restrict database port access
- [ ] Enable MySQL general query log ONLY during debugging

#### 7. File Security
- [ ] Remove database.sql from production (it contains schema and credentials)
- [ ] Remove all .git directories from production
- [ ] Disable directory listing (Options -Indexes in Apache)
- [ ] Protect sensitive files (.htaccess, config files)
- [ ] Set proper permissions:
  ```bash
  # Directories: 755
  find . -type d -exec chmod 755 {} \;
  # Files: 644
  find . -type f -exec chmod 644 {} \;
  # Uploads: 755
  chmod 755 uploads/complaints/
  # Logs: 755
  chmod 755 logs/
  ```

#### 8. PHP Security
- [ ] Disable dangerous functions in php.ini:
  - [ ] exec
  - [ ] passthru
  - [ ] shell_exec
  - [ ] system
  - [ ] proc_open
  - [ ] popen
- [ ] Set `display_errors = Off` in php.ini
- [ ] Set `log_errors = On` in php.ini
- [ ] Increase `memory_limit` if needed (e.g., 256M)
- [ ] Set `upload_max_filesize = 10M`
- [ ] Set `post_max_size = 10M`
- [ ] Set `max_execution_time = 300`
- [ ] Enable `session.secure = 1` for HTTPS
- [ ] Enable `session.httponly = 1`
- [ ] Set `session.samesite = 'Strict'`

#### 9. Firewall & Access Control
- [ ] Configure firewall to allow only necessary ports (80, 443)
- [ ] Restrict database access to local connections only
- [ ] Set up IP whitelisting for admin panel (optional)
- [ ] Create separate admin user account on server
- [ ] Disable root SSH login
- [ ] Set up key-based authentication for SSH

### Performance

#### 10. Database Optimization
- [ ] Verify all indexes are in place
- [ ] Check query execution times for slow queries
- [ ] Enable query caching if supported
- [ ] Set up automated database maintenance
- [ ] Monitor database size and optimize tables

#### 11. Caching
- [ ] Consider implementing Redis/Memcached for sessions
- [ ] Enable browser caching for static assets
- [ ] Set appropriate Cache-Control headers
- [ ] Minify CSS and JavaScript files (optional)
- [ ] Combine multiple CSS/JS files if necessary

#### 12. Content Delivery
- [ ] Set up CDN for static assets (if serving many users)
- [ ] Enable gzip compression
- [ ] Optimize image sizes
- [ ] Use async/defer for JavaScript loading

### Monitoring

#### 13. Logging & Monitoring
- [ ] Set up centralized logging (if multiple servers)
- [ ] Configure error log rotation
- [ ] Set up server monitoring (CPU, RAM, Disk)
- [ ] Set up uptime monitoring
- [ ] Configure email alerts for errors and warnings
- [ ] Monitor database performance
- [ ] Set up application-level logging for errors

#### 14. Backup & Recovery
- [ ] Verify automated daily database backups
- [ ] Test backup restoration procedure
- [ ] Store backups in off-site location
- [ ] Document backup retention policy
- [ ] Create disaster recovery plan
- [ ] Test disaster recovery procedure

#### 15. Health Checks
- [ ] Create `/health.php` endpoint for monitoring
- [ ] Set up uptime monitoring service
- [ ] Configure alerts for downtime
- [ ] Test all critical user flows in production

### Operations

#### 16. Documentation
- [ ] Document admin user management procedure
- [ ] Create procedure for resetting user password
- [ ] Document how to export reports
- [ ] Create incident response procedures
- [ ] Document contact list for emergencies
- [ ] Update SETUP_GUIDE.md with production-specific notes

#### 17. User Onboarding
- [ ] Send initial credentials to admin accounts via secure channel
- [ ] Provide admin training on dashboard usage
- [ ] Provide admin training on complaint management
- [ ] Provide student instructions on complaint submission
- [ ] Create FAQ for common issues
- [ ] Set up support email address

#### 18. Final Checks
- [ ] Verify DNS is pointing to server
- [ ] Test main URL accessibility
- [ ] Verify HTTPS certificate is valid
- [ ] Test all forms and functionality
- [ ] Verify responsive design on production
- [ ] Check error logs for any issues
- [ ] Load test with expected user volume
- [ ] Review audit log for suspicious activity

---

## Post-Deployment

### Week 1
- [ ] Monitor system for errors and performance issues
- [ ] Review audit logs daily
- [ ] Check database backup completion
- [ ] Monitor disk space
- [ ] Verify all users can access their functions
- [ ] Collect feedback from initial users

### Week 2-4
- [ ] Fix any reported issues
- [ ] Optimize slow queries based on logs
- [ ] Adjust session timeout if needed
- [ ] Fine-tune file upload limits if needed
- [ ] Ensure admin staff are comfortable with dashboard
- [ ] Plan for ongoing maintenance

### Ongoing
- [ ] Monitor system health daily
- [ ] Review audit logs weekly
- [ ] Backup verification monthly
- [ ] Security updates as released
- [ ] Performance analysis monthly
- [ ] User feedback collection and implementation
- [ ] Database optimization as needed

---

## Emergency Procedures

### Database Recovery
```bash
# Restore from backup
mysql delasalle_complaints < backup_2024-01-15.sql

# Verify restore
mysql delasalle_complaints -e "SELECT COUNT(*) FROM complaints;"
```

### Reset Admin Password
```sql
-- If admin account is locked
UPDATE users SET status = 'active' WHERE username = 'admin';

-- Reset password to temporary value
UPDATE users SET password = PASSWORD('temp_password') WHERE username = 'admin';
-- (Or use hash_password('temp_password') from db_connection.php)
```

### Clear Sessions
```sql
DELETE FROM /* session storage location */ WHERE session_expired < NOW();
```

### Disable Maintenance Mode
1. Edit `config.php`
2. Set `CONFIG_MAINTENANCE_MODE = false`
3. Save and test

---

## Security Audit Checklist

### Code Security
- [ ] All database queries use prepared statements
- [ ] All user input is validated and sanitized
- [ ] All file uploads are validated for type and size
- [ ] Session validation is performed on every protected page
- [ ] Password hashing uses bcrypt with salt

### Infrastructure Security
- [ ] HTTPS enabled and enforced
- [ ] Database user has minimal required privileges
- [ ] Server firewall is properly configured
- [ ] SSH uses key-based authentication
- [ ] Regular security patches are applied
- [ ] File permissions are correctly set

### Access Control
- [ ] Role-based access control (admin vs student) is enforced
- [ ] Admin functionality is protected
- [ ] Student can only see their own submissions
- [ ] Logout functionality clears session properly
- [ ] Session timeout is enforced

### Data Protection
- [ ] Sensitive information is logged but not exposed
- [ ] Database backups are encrypted
- [ ] Backup location is secure and off-site
- [ ] Audit trail is comprehensive and tamper-evident
- [ ] Personal information is not exposed in URLs

---

## Contact & Support

**Technical Support Email:** support@delasalle.edu.ph
**Admin Support Email:** admin@delasalle.edu.ph
**Emergency Contact:** +63 (2) 1234-5678

---

*Last Updated: 2024*
*Document Version: 1.0*
