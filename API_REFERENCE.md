# API Reference - Student Complaint Portal

## Overview

This document describes all API endpoints available in the Student Complaint Portal. All endpoints return JSON responses and require active session (except login/register endpoints).

## Authentication Endpoints

### Login
**Endpoint**: `POST /process_login.php`

**Parameters**:
```json
{
  "username": "admin",
  "password": "admin123",
  "remember_me": false
}
```

**Response (Success)**:
```json
{
  "success": true,
  "user_type": "admin",
  "redirect_to": "admin_dashboard.php"
}
```

**Response (Error)**:
```json
{
  "success": false,
  "error": "Invalid username or password"
}
```

---

### Register
**Endpoint**: `POST /process_register.php`

**Parameters**:
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "09123456789",
  "course": "BS Computer Science",
  "department": "Computer Science",
  "username": "johndoe",
  "password": "password123",
  "confirm_password": "password123"
}
```

**Response (Success)**:
```json
{
  "success": true,
  "message": "Account created successfully"
}
```

**Response (Error)**:
```json
{
  "success": false,
  "errors": {
    "email": "Email already exists",
    "username": "Username must be 5-20 characters"
  }
}
```

---

## Dashboard Endpoints

### Get Dashboard Data (Student)
**Endpoint**: `GET /get_dashboard_data.php`

**Response**:
```json
{
  "success": true,
  "stats": {
    "total_submissions": 5,
    "pending": 2,
    "under_review": 1,
    "resolved": 2
  },
  "unread_notifications": 3,
  "announcements": [
    {
      "id": 1,
      "title": "System Maintenance",
      "message": "System will be down...",
      "created_at": "2024-01-15 10:00:00"
    }
  ],
  "recent_submissions": [
    {
      "id": 1,
      "reference_number": "COMPLAINT-20240115100000-1234",
      "type": "Complaint",
      "status": "Under Review",
      "submitted_date": "2024-01-15"
    }
  ]
}
```

---

### Get Admin Dashboard Data
**Endpoint**: `GET /get_admin_dashboard.php`

**Response**:
```json
{
  "success": true,
  "stats": {
    "total_complaints": 45,
    "pending_complaints": 12,
    "total_feedback": 23,
    "resolved": 28
  },
  "recent_complaints": [
    {
      "complaint_id": 1,
      "reference_number": "COMPLAINT-20240115100000-1234",
      "student_name": "John Doe",
      "complaint_type": "Academic",
      "status": "Under Review",
      "created_at": "2024-01-15 10:00:00"
    }
  ]
}
```

---

## Complaint Endpoints

### Submit Complaint
**Endpoint**: `POST /process_complaint.php`

**Parameters** (multipart/form-data):
```
complaint_type: "Academic"
course_department: "BS Computer Science"
date_of_incident: "2024-01-10"
description: "The professor was unfair in grading..."
files: [file1, file2, ...]
```

**Response (Success)**:
```json
{
  "success": true,
  "complaint_id": 1,
  "reference_number": "COMPLAINT-20240115100000-1234",
  "message": "Complaint submitted successfully"
}
```

**Response (Error)**:
```json
{
  "success": false,
  "error": "Description must be at least 20 characters"
}
```

---

### Get User Submissions
**Endpoint**: `GET /get_submissions.php?type=all&status=all&sort=newest`

**Query Parameters**:
- `type`: "all", "complaints", "feedback"
- `status`: "all", "Submitted", "Under Review", "In Progress", "Resolved", "Closed", "Rejected"
- `sort`: "newest", "oldest"

**Response**:
```json
{
  "success": true,
  "submissions": [
    {
      "id": 1,
      "reference_number": "COMPLAINT-20240115100000-1234",
      "type_name": "Academic",
      "status": "Under Review",
      "submitted_date": "2024-01-15",
      "updated_date": "2024-01-16",
      "type": "complaint"
    },
    {
      "id": 2,
      "reference_number": "FEEDBACK-20240115100100-5678",
      "type_name": "Suggestion",
      "status": "Submitted",
      "submitted_date": "2024-01-15",
      "updated_date": "2024-01-15",
      "type": "feedback"
    }
  ]
}
```

---

### Get Complaint/Feedback Details
**Endpoint**: `GET /get_detail.php?id=1&type=complaint`

**Query Parameters**:
- `id`: Complaint/Feedback ID
- `type`: "complaint", "feedback"

**Response**:
```json
{
  "success": true,
  "complaint": {
    "complaint_id": 1,
    "reference_number": "COMPLAINT-20240115100000-1234",
    "complaint_type": "Academic",
    "course_department": "BS Computer Science",
    "description": "The professor was unfair...",
    "date_of_incident": "2024-01-10",
    "status": "Under Review",
    "created_at": "2024-01-15 10:00:00",
    "updated_at": "2024-01-16 14:30:00"
  },
  "user_data": {
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "course": "BS Computer Science"
  },
  "files": [
    {
      "file_id": 1,
      "original_filename": "evidence.pdf",
      "file_size": 102400,
      "uploaded_at": "2024-01-15 10:00:00"
    }
  ],
  "remarks": [
    {
      "remark_id": 1,
      "remarks": "We are reviewing your case...",
      "status_update": "Under Review",
      "created_at": "2024-01-15 15:00:00"
    }
  ],
  "history": [
    {
      "old_status": "Submitted",
      "new_status": "Under Review",
      "changed_at": "2024-01-15 15:00:00"
    }
  ]
}
```

---

### Download File
**Endpoint**: `GET /uploads/complaints/[filename]`

**Note**: Direct file download from uploads directory. Filename generated by system (e.g., "1704042000_evidence.pdf")

---

## Feedback Endpoints

### Submit Feedback
**Endpoint**: `POST /process_feedback.php`

**Parameters**:
```json
{
  "feedback_type": "Suggestion",
  "department_office": "Academic Affairs",
  "description": "Consider offering more programming courses..."
}
```

**Response (Success)**:
```json
{
  "success": true,
  "feedback_id": 1,
  "reference_number": "FEEDBACK-20240115100100-5678",
  "message": "Feedback submitted successfully"
}
```

---

## Notification Endpoints

### Get Notifications
**Endpoint**: `GET /get_notifications.php?filter=all`

**Query Parameters**:
- `filter`: "all", "unread", notification_type

**Response**:
```json
{
  "success": true,
  "notifications": [
    {
      "notification_id": 1,
      "title": "Complaint Status Updated",
      "message": "Your complaint COMPLAINT-20240115100000-1234 has been updated to Under Review",
      "notification_type": "complaint_updated",
      "is_read": false,
      "created_at": "2024-01-15 10:00:00"
    }
  ]
}
```

---

### Mark Notification as Read
**Endpoint**: `POST /mark_notification_read.php`

**Parameters**:
```json
{
  "notification_id": 1
}
```

**Response**:
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

---

### Mark All Notifications as Read
**Endpoint**: `POST /mark_all_read.php`

**Response**:
```json
{
  "success": true,
  "message": "All notifications marked as read"
}
```

---

## Profile Endpoints

### Get User Profile
**Endpoint**: `GET /get_profile.php`

**Response**:
```json
{
  "success": true,
  "user": {
    "user_id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "09123456789",
    "username": "johndoe",
    "course": "BS Computer Science",
    "department": "Computer Science",
    "user_type": "student"
  }
}
```

---

### Update Profile
**Endpoint**: `POST /update_profile.php`

**Parameters**:
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "newemail@example.com",
  "phone": "09123456789",
  "course": "BS Information Technology",
  "department": "Information Technology"
}
```

**Response (Success)**:
```json
{
  "success": true,
  "message": "Profile updated successfully"
}
```

**Response (Error)**:
```json
{
  "success": false,
  "error": "Email already exists"
}
```

---

### Change Password
**Endpoint**: `POST /change_password.php`

**Parameters**:
```json
{
  "current_password": "oldpass123",
  "new_password": "newpass123",
  "confirm_password": "newpass123"
}
```

**Response (Success)**:
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

**Response (Error)**:
```json
{
  "success": false,
  "error": "Current password is incorrect"
}
```

---

## Admin Endpoints

### Get All Complaints
**Endpoint**: `GET /get_all_complaints.php?status=all&type=all&department=&sort=newest`

**Query Parameters**:
- `status`: "all", "Submitted", "Under Review", "In Progress", "Resolved", "Closed", "Rejected"
- `type`: "all", "Academic", "Facility", "Staff", "Misconduct", "Others"
- `department`: Text search filter (optional)
- `sort`: "newest", "oldest"

**Response**:
```json
{
  "success": true,
  "complaints": [
    {
      "complaint_id": 1,
      "reference_number": "COMPLAINT-20240115100000-1234",
      "student_name": "John Doe",
      "complaint_type": "Academic",
      "course_department": "BS Computer Science",
      "status": "Under Review",
      "created_at": "2024-01-15 10:00:00"
    }
  ]
}
```

---

### Get Complaint (Admin)
**Endpoint**: `GET /admin_get_complaint.php?id=1`

**Query Parameters**:
- `id`: Complaint ID

**Response**:
```json
{
  "success": true,
  "complaint": {
    "complaint_id": 1,
    "reference_number": "COMPLAINT-20240115100000-1234",
    "complaint_type": "Academic",
    "status": "Under Review",
    "description": "...",
    "date_of_incident": "2024-01-10",
    "created_at": "2024-01-15 10:00:00"
  },
  "student": {
    "user_id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "course": "BS Computer Science"
  },
  "files": [...],
  "remarks": [...]
}
```

---

### Update Complaint (Admin)
**Endpoint**: `POST /admin_update_complaint.php`

**Parameters**:
```json
{
  "complaint_id": 1,
  "new_status": "In Progress",
  "remarks": "We have assigned this to the Dean's office for investigation."
}
```

**Response (Success)**:
```json
{
  "success": true,
  "message": "Complaint updated successfully",
  "old_status": "Under Review",
  "new_status": "In Progress"
}
```

**Response (Error)**:
```json
{
  "success": false,
  "error": "Complaint not found"
}
```

---

## Error Responses

### General Error Format
```json
{
  "success": false,
  "error": "Error message",
  "status_code": 400
}
```

### Common Error Codes
- `401`: Unauthorized (no active session)
- `403`: Forbidden (insufficient permissions)
- `404`: Not found (resource doesn't exist)
- `422`: Validation error (invalid data)
- `500`: Server error (database/system issue)

### Example Error Response
```json
{
  "success": false,
  "error": "You must be logged in to access this resource",
  "status_code": 401
}
```

---

## Response Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid parameters |
| 401 | Unauthorized | Not logged in |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation error |
| 500 | Internal Server Error | Database/server error |

---

## Request Headers

All requests should include:
```
Content-Type: application/json
```

For file uploads, use:
```
Content-Type: multipart/form-data
```

---

## Response Headers

All responses include:
```
Content-Type: application/json
X-Powered-By: DSJBC Portal
```

---

## Rate Limiting

Current system has no rate limiting. For production, consider implementing:
- Max 100 requests per minute per IP
- Max 10 login attempts per 15 minutes
- Max 50 file uploads per day per user

---

## CORS Policy

Current implementation assumes same-origin requests. For API access from different domains:

Add to response headers:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

---

## Webhook Integration (Future)

Planned webhooks for third-party integrations:
- `complaint.created` - When new complaint submitted
- `complaint.status_changed` - When admin updates status
- `complaint.resolved` - When complaint marked resolved

---

## SDK / Library Support

Currently, no official SDK is provided. Common usage:

### JavaScript (Fetch)
```javascript
fetch('/get_submissions.php', {
  method: 'GET',
  credentials: 'include'
})
.then(response => response.json())
.then(data => console.log(data));
```

### PHP (cURL)
```php
$ch = curl_init('http://localhost/get_submissions.php');
curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . session_id());
$response = curl_exec($ch);
$data = json_decode($response, true);
```

### Python (Requests)
```python
import requests

session = requests.Session()
response = session.get('http://localhost/get_submissions.php')
data = response.json()
```

---

## Versioning

Current API Version: 1.0

No versioning prefix in URLs. Future versions may use:
- `/api/v1/complaints/`
- `/api/v2/complaints/`

---

## Best Practices

1. **Always include error handling** - Check `success` field
2. **Validate responses** - Check for required fields
3. **Handle timeouts** - Set appropriate timeout values
4. **Cache appropriately** - Dashboard data can be cached for 5 minutes
5. **Secure credentials** - Never expose passwords or API keys
6. **Use HTTPS** - All production requests should be encrypted
7. **Log requests** - Maintain audit trail for compliance

---

## Testing

### cURL Examples

```bash
# Login
curl -X POST http://localhost/process_login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'

# Get submissions
curl -X GET http://localhost/get_submissions.php \
  -H "Cookie: PHPSESSID=your_session_id"

# Submit complaint
curl -X POST http://localhost/process_complaint.php \
  -H "Cookie: PHPSESSID=your_session_id" \
  -F "complaint_type=Academic" \
  -F "course_department=CS" \
  -F "date_of_incident=2024-01-10" \
  -F "description=Test complaint" \
  -F "files=@document.pdf"
```

---

## Support

For API questions or issues:
- Email: support@delasalle.edu.ph
- Check: README.md, SETUP_GUIDE.md
- Review: Error messages and status codes

---

*Last Updated: 2024*
*Document Version: 1.0*
