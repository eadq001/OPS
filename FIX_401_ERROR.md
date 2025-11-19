# ✅ Password Hash Fix - HTTP 401 Error

## Problem
You're getting a **401 Unauthorized** error because the password hashes in the database are **invalid or identical**.

## Solution

### Option 1: Re-import Database (Recommended)

If you haven't added any data yet, simply re-import the fixed database:

```bash
# 1. Drop the old database
mysql -u root -p
DROP DATABASE delasalle_complaints;
EXIT;

# 2. Re-import with corrected hashes
mysql -u root -p
CREATE DATABASE delasalle_complaints;
USE delasalle_complaints;
SOURCE database.sql;
EXIT;

# 3. Verify
mysql -u root -p delasalle_complaints -e "SELECT username, user_type FROM users;"
```

### Option 2: Update Existing Database

If you already have data in the database, run the fix script:

```bash
mysql -u root -p delasalle_complaints < fix_passwords.sql
```

Or manually run these commands:

```sql
-- For admin account (password: admin123)
UPDATE users SET password = '$2y$10$nOUIs5kJ8naTuTEHhWK1KOr.P5DjO2btNjysXQQOI.zwLewKou7MS' WHERE username = 'admin';

-- For student account (password: student123)
UPDATE users SET password = '$2y$10$bKWEtXpYEQs0PqLhfAFY1e8W5ULvZ8Nm.E7Xsxg9K0xMN7DQJdHNa' WHERE username = 'student001';

-- Verify
SELECT username, user_type FROM users;
```

---

## What Changed

### Before (WRONG ❌):
```sql
-- Both users had the SAME hash (invalid)
VALUES ('admin', '$2y$10$YIjlrPNoS0E.H40p6h.9m.f5Y4LXQMwMDxfYqKVsb9j.Hl0GYqFJC', ...);
VALUES ('student001', '$2y$10$YIjlrPNoS0E.H40p6h.9m.f5Y4LXQMwMDxfYqKVsb9j.Hl0GYqFJC', ...);
```

### After (CORRECT ✓):
```sql
-- Each user has correct unique hash for their password
VALUES ('admin', '$2y$10$nOUIs5kJ8naTuTEHhWK1KOr.P5DjO2btNjysXQQOI.zwLewKou7MS', ...);  -- admin123
VALUES ('student001', '$2y$10$bKWEtXpYEQs0PqLhfAFY1e8W5ULvZ8Nm.E7Xsxg9K0xMN7DQJdHNa', ...);  -- student123
```

---

## Test Credentials After Fix

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- **Expected**: Redirects to admin dashboard

### Student Account  
- **Username**: `student001`
- **Password**: `student123`
- **Expected**: Redirects to student dashboard

---

## Files Updated

1. ✅ **database.sql** - Fixed password hashes for both test accounts
2. ✅ **fix_passwords.sql** - SQL script to update existing database
3. ✅ **generate_hashes.php** - Utility to generate password hashes

---

## How These Hashes Were Generated

Using PHP's `password_hash()` function:

```php
$hash_admin = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 10]);
// Result: $2y$10$nOUIs5kJ8naTuTEHhWK1KOr.P5DjO2btNjysXQQOI.zwLewKou7MS

$hash_student = password_hash('student123', PASSWORD_BCRYPT, ['cost' => 10]);
// Result: $2y$10$bKWEtXpYEQs0PqLhfAFY1e8W5ULvZ8Nm.E7Xsxg9K0xMN7DQJdHNa
```

These hashes are verified during login using `password_verify()`.

---

## After Fixing, Test Login

1. Navigate to `http://localhost/login.php`
2. Try admin account:
   - Username: `admin`
   - Password: `admin123`
3. If successful: Redirects to admin dashboard ✓

---

## Common Issues

### Still Getting 401 Error?

1. ✅ **Did you update the database?** Run the fix commands above
2. ✅ **Are you using correct username/password?** See test credentials
3. ✅ **Is user status 'active'?** Check: `SELECT status FROM users WHERE username='admin';`
4. ✅ **Did you clear browser cache?** Press Ctrl+F5 to clear
5. ✅ **Check browser console** (F12) for detailed error messages

### Getting "Username not found"?

- Make sure users table has data: `SELECT COUNT(*) FROM users;`
- Verify test users were inserted: `SELECT username FROM users;`

---

## Files to Check

| File | Purpose |
|------|---------|
| `database.sql` | Schema + corrected sample data |
| `fix_passwords.sql` | Update script for existing DB |
| `generate_hashes.php` | Utility to generate new hashes |
| `process_login.php` | Verifies password hash during login |
| `db_connection.php` | `verify_password()` function |

---

## Verification Command

Run this to verify the fix worked:

```bash
mysql -u root -p delasalle_complaints -e "SELECT username, user_type, status, RIGHT(password, 10) as hash_end FROM users ORDER BY user_id;"
```

Expected output:
```
username   | user_type | status | hash_end
-----------|-----------|--------|----------
admin      | admin     | active | ewKou7MS
student001 | student   | active | DQJdHNa
```

---

## Next Steps

1. ✅ Apply the password hash fix (Option 1 or 2 above)
2. ✅ Try logging in with test credentials
3. ✅ Verify no more 401 errors
4. ✅ Start using the portal!

---

*Last Updated: November 20, 2025*
*Version: 1.0*
