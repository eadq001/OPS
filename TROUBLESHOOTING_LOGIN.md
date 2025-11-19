# Login & Registration Troubleshooting Guide

## Issues Fixed ✅

The following issues have been identified and **fixed**:

### 1. **"An error occurred. Please try again." on Login**

**Cause**: 
- Weak error handling in JavaScript fetch calls
- Headers not properly set for JSON responses
- Missing response status code checks

**Fix Applied**:
- ✅ Enhanced error handling with try-catch in fetch
- ✅ Added proper response validation
- ✅ Added console error logging for debugging
- ✅ Updated field name from `redirect` to `redirect_to`
- ✅ Added HTTP status codes in responses
- ✅ Better error messages with specific error codes

### 2. **"An error occurred. Please try again." on Registration**

**Cause**:
- Similar JavaScript error handling issues
- Missing validation error messages
- Insufficient error detail in responses

**Fix Applied**:
- ✅ Improved error handling in register.php
- ✅ Added detailed error messages
- ✅ Better field validation
- ✅ Proper HTTP status codes
- ✅ More descriptive error codes

---

## Database Setup Required ⚠️

The system needs the database to be initialized before login/registration will work.

### Step 1: Create Database

```bash
mysql -u root -p
```

Then run these commands:

```sql
CREATE DATABASE delasalle_complaints;
USE delasalle_complaints;
SOURCE database.sql;
```

### Step 2: Verify Tables Created

```sql
SHOW TABLES;
```

You should see these tables:
- users
- complaints
- complaint_files
- feedback
- admin_remarks
- status_history
- notifications
- announcements
- audit_log

### Step 3: Check Test User

```sql
SELECT user_id, username, user_type, status FROM users WHERE username = 'admin';
```

Should return:
```
user_id | username | user_type | status
--------|----------|-----------|--------
    1   | admin    | admin     | active
    2   | student001 | student  | active
```

---

## Test Credentials

After database setup, you can login with:

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`

### Student Account
- **Username**: `student001`
- **Password**: `student123`

---

## How to Debug

### In Browser Console (Press F12)

#### Step 1: Open Developer Tools
- Press `F12` on keyboard
- Go to "Console" tab

#### Step 2: Try Login
- Fill in username and password
- Click Login
- Check Console for errors

#### Step 3: Check Network Tab
- Go to "Network" tab
- Try Login again
- Click on `process_login.php` request
- Look at "Response" tab to see the actual error

### Common Response Messages

#### "Database Connection Failed"
**Solution**: 
- Check if MySQL server is running
- Verify database credentials in `db_connection.php`
- Ensure database `delasalle_complaints` exists

#### "Username not found or account is inactive"
**Solution**:
- Check if test data was imported from `database.sql`
- User account may be inactive (check `status` field)
- Verify username spelling

#### "Invalid password"
**Solution**:
- Password is case-sensitive
- Default admin password is: `admin123`
- Default student password is: `student123`

#### "Query execution failed" or "Database error occurred"
**Solution**:
- Check MySQL connection
- Verify database tables exist
- Look at MySQL error logs
- Run `database.sql` again to recreate tables

---

## File Changes Made

### Updated Files:

1. **process_login.php**
   - ✅ Added input validation
   - ✅ Better error messages
   - ✅ HTTP status codes
   - ✅ More descriptive error codes

2. **login.php** (JavaScript)
   - ✅ Improved fetch error handling
   - ✅ Response validation
   - ✅ Console logging
   - ✅ Updated field names

3. **process_register.php**
   - ✅ Better error handling
   - ✅ Field validation
   - ✅ HTTP status codes
   - ✅ Detailed error messages

4. **register.php** (JavaScript)
   - ✅ Improved fetch error handling
   - ✅ Better error messages
   - ✅ Console logging

5. **db_connection.php**
   - ✅ Removed duplicate headers (prevents header conflicts)
   - ✅ Cleaner session configuration

---

## Verification Checklist

Before using the portal, verify:

- [ ] MySQL server is running
- [ ] Database `delasalle_complaints` is created
- [ ] All 10 tables exist in database
- [ ] Test users exist (admin, student001)
- [ ] Users table has hashed passwords
- [ ] All users have `status = 'active'`
- [ ] Browser console shows no errors
- [ ] Network tab shows 200 status codes

---

## Quick Setup Commands

Run these commands to set up everything:

```bash
# 1. Connect to MySQL
mysql -u root -p

# 2. Create database
CREATE DATABASE delasalle_complaints;
USE delasalle_complaints;

# 3. Import schema
SOURCE /path/to/database.sql;

# 4. Verify
SHOW TABLES;
SELECT COUNT(*) FROM users;

# 5. Exit MySQL
EXIT;
```

---

## If Login Still Doesn't Work

### Option 1: Check Browser Console
1. Press F12
2. Go to Console tab
3. Look for red error messages
4. Copy the error and search for it below

### Option 2: Check PHP Errors
1. Check Apache/Nginx error logs
2. Look for PHP parse errors
3. Verify `db_connection.php` syntax

### Option 3: Manual Database Check

```bash
# Check if user exists
mysql -u root -p delasalle_complaints -e "SELECT * FROM users WHERE username='admin';"

# Check password hash
mysql -u root -p delasalle_complaints -e "SELECT username, password FROM users LIMIT 1;"

# Check audit log
mysql -u root -p delasalle_complaints -e "SELECT * FROM audit_log ORDER BY action_timestamp DESC LIMIT 5;"
```

### Option 4: Test Database Connection

Create a test file `test_db.php`:

```php
<?php
include 'db_connection.php';

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    echo "Connected successfully!";
    
    // Test query
    $result = $conn->query("SELECT COUNT(*) as user_count FROM users");
    $row = $result->fetch_assoc();
    echo "<br>Users in database: " . $row['user_count'];
    
    $conn->close();
}
?>
```

Then visit `http://localhost/test_db.php` in browser.

---

## Common Error Messages & Solutions

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Database Connection Failed" | MySQL not running or wrong credentials | Start MySQL, check `db_connection.php` |
| "Username not found" | User doesn't exist or typo | Import `database.sql`, check spelling |
| "Invalid password" | Wrong password or hash mismatch | Use `admin123` or `student123`, check hash |
| "Account is inactive" | User status is not 'active' | Update: `UPDATE users SET status = 'active';` |
| "Query execution failed" | SQL syntax error or table missing | Run `database.sql` again |
| "An error occurred" (generic) | Various issues | Check browser console (F12) for details |
| "Network error" | Server not responding | Check if PHP server is running |

---

## If Registration Still Fails

### Common Registration Issues

1. **"Username already exists"**
   - The username is taken
   - Solution: Choose a different username

2. **"Email already registered"**
   - The email is already used
   - Solution: Use a different email address

3. **"Username must be 5-20 characters"**
   - Username too short or too long
   - Solution: Use 5-20 characters

4. **"Username can only contain letters, numbers, and underscores"**
   - Invalid characters used
   - Solution: Use only `a-z`, `A-Z`, `0-9`, `_`

5. **"Password must be at least 8 characters"**
   - Password too short
   - Solution: Use 8+ characters

6. **"Passwords do not match"**
   - Confirm password different
   - Solution: Make sure both passwords are identical

---

## Next Steps

Once login is working:

1. Log in with admin account
2. Go to admin dashboard
3. Log out and log in with student account
4. Explore student dashboard
5. Try submitting a complaint
6. Check notifications
7. Review QUICK_REFERENCE.md for all features

---

## Still Having Issues?

1. **Check all the solutions above first**
2. **Review browser console (F12)** for detailed errors
3. **Check database with MySQL commands** to verify data
4. **Verify all files were created** in the OPS folder
5. **Contact support**: support@delasalle.edu.ph

---

## References

- **Database Schema**: See `database.sql`
- **Configuration**: See `db_connection.php`
- **API Details**: See `API_REFERENCE.md`
- **Setup Instructions**: See `SETUP_GUIDE.md`

---

*Last Updated: November 20, 2025*
*Version: 2.0 (Fixed)*
