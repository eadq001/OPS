#!/bin/bash
# Quick Fix Script for HTTP 401 Error
# Usage: Run this script in terminal to fix password hashes

echo "=========================================="
echo "Fixing HTTP 401 Error - Password Hashes"
echo "=========================================="
echo ""

# Check if user wants to re-import or update
echo "Choose an option:"
echo "1) Re-import database (drops and recreates) - RECOMMENDED if no data"
echo "2) Update existing database (keeps your data)"
echo ""
read -p "Enter your choice (1 or 2): " choice

if [ "$choice" = "1" ]; then
    echo ""
    echo "Step 1: Connecting to MySQL and dropping old database..."
    mysql -u root -p -e "DROP DATABASE IF EXISTS delasalle_complaints;"
    
    echo "Step 2: Creating new database..."
    mysql -u root -p -e "CREATE DATABASE delasalle_complaints;"
    
    echo "Step 3: Importing schema with correct password hashes..."
    mysql -u root -p delasalle_complaints < database.sql
    
    echo ""
    echo "✅ Database re-imported successfully!"
    
elif [ "$choice" = "2" ]; then
    echo ""
    echo "Updating existing database with correct password hashes..."
    mysql -u root -p delasalle_complaints < fix_passwords.sql
    
    echo ""
    echo "✅ Password hashes updated successfully!"
    
else
    echo "❌ Invalid choice. Please run again and select 1 or 2."
    exit 1
fi

echo ""
echo "Verifying the fix..."
mysql -u root -p delasalle_complaints -e "SELECT username, user_type, status FROM users;"

echo ""
echo "=========================================="
echo "✅ FIX COMPLETE!"
echo "=========================================="
echo ""
echo "You can now login with:"
echo "  Admin:   admin / admin123"
echo "  Student: student001 / student123"
echo ""
